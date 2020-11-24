<?php

namespace App\Http\Controllers\PromoteData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Console\Input\Input;
use App\Models\EmailConfig;
use App\Models\EmailData;
use App\Models\EmailPass;
use Illuminate\Support\Facades\Storage;
use PhpImap\Mailbox;
use Illuminate\Support\Facades\DB;

class PromoteDataController extends Controller
{
    //邮件数据
    public function get_mail_list(Request $request){
        ini_set('max_execution_time', '0');
    	$user_id = $request->get('user', '');
    	//先去查询出可以读取的邮件列表
        $from_mail_list = EmailPass::where('user_id', $user_id)->get();
        $from_mail_arr = [];//可以被获取的收件人的邮件（在这个邮件列表中的邮箱可以抓取内容）
        foreach ($from_mail_list as $key=>$single_from_mail){
            $from_mail_arr[] = $single_from_mail->email_account;
        }

    	//查询属于这个用户的邮件账号，同时查询出状态为可用的（1）
    	$config_data = EmailConfig::where('user_id', $user_id)->where('status','>','0')->where('type','=','mail')->get()->toarray();
        // var_dump($config_data);
        // die();
    	foreach ($config_data as $key=>$value){
    		//进行邮箱数据的读取
            //需要先进行
            echo '<br/>循环开始'.$key;
    		$this->get_single_mail_data($value,$from_mail_arr);
    	}

    }
    //获取邮箱账号的相关的邮件
    public function get_single_mail_data($data,$from_mail_arr){

        $mailServer=$data['host_port']; //IMAP主机
        // var_dump($data,$from_mail_arr);
        // die();

        $mailbox = new Mailbox(
            "{{$mailServer}}INBOX", // IMAP server and mailbox folder
            $data['email_address'], // Username for the before configured mailbox
            $data['email_password'], // Password for the before configured username
            null, // Directory, where attachments will be saved (optional)
            'UTF-8' // Server encoding (optional)
        );
        $mailbox->setAttachmentsIgnore(true);

        // var_dump($mailbox);
        // die();
        try {
            // Get all emails (messages)
            // PHP.net imap_search criteria: http://php.net/manual/en/function.imap-search.php
            $mailsIds = $mailbox->searchMailbox('UNSEEN');  // 结果为：array(2) { [0]=> int(3) [1]=> int(4) }
        } catch(PhpImap\Exceptions\ConnectionException $ex) {
            echo "连接出错，稍后再试 " . $ex;
            return true;
            // die();
        }
        // var_dump($mailsIds);
        // die();

        // If $mailsIds is empty, no emails could be found
        if(!$mailsIds) {
            echo '并未收到新的信息邮件';
            return ;
        }

        // Get the first message
        // If '__DIR__' was defined in the first line, it will automatically
        // save all attachments to the specified directory
        $be_to_flaged = [];
        $total_mail_data = [];
        foreach ($mailsIds as $key=>$mailId){//循环符合条件邮件id列表

            $email = $mailbox->getMail($mailId,false);

            //处理相关信息的分离和整合
            $from_email = $email->fromAddress;

            //判断此邮件发件人是否在执行列表中
            if (!in_array($from_email,$from_mail_arr)){
                continue;
            }else{

                $email_title = $email->subject;
                $email_date =date('Y-m-d H:i:s', strtotime($email->date));
                $email_content = '';

                if(!empty($email->textHtml)){
                    $email_content = $email->textHtml;
                }elseif (!empty($email->textPlain)) {
                    $email_content = $email->textPlain;
                }else{
                    echo 'continue';
                    continue;
                }
                echo 'html:'.$email->textHtml.'<br/>';
                echo 'plain:'.$email->textPlain.'<br/>';

//                $mail_data = [];
                if ($from_email == '2162750756@qq.com'){//此信息由公司内部模板发出
                    //姓名、电话、来源
                    $mail_data = $this->deal_inside_mail($email_content);
                }else{ //是由的外部模板
                    $mail_data = $this->deal_outside_mail($email_content);
                }

                echo '<pre>';
                var_dump($mail_data);
                echo '</pre>';


                $mail_data['from_mail'] = $from_email;
                $mail_data['mail_title'] = $email_title;
                $mail_data['mail_date'] = $email_date;
                $mail_data['mail_content'] = $email_content;

                //获取到了数据，进行插入数据库
                $mail_data['econfig_id'] = $data['id'];
                $mail_data['user_id'] = $data['user_id'];
                $total_mail_data[] = $mail_data;

                $be_to_flaged[] = $mailId;//将已经记录好的邮件id记录，准备下步进行星标


            }//此邮件在认证列表中

        }

        //邮件循环完毕，进行插入数据库和更改状态
        var_dump($total_mail_data);
        try
        {
            DB::connection()->enableQueryLog();
            var_dump('插入的数据：',json_encode($total_mail_data));
            // die();
            $insert_status = DB::table('email_data')->insert($total_mail_data);
            dump(DB::getQueryLog());
            if(empty($be_to_flaged)){
                var_dump('邮件ID列表为空');
            }else{
                var_dump('邮件ID列表有值:');
                var_dump($be_to_flaged);
                $mailbox->setFlag($be_to_flaged,'\\Seen \\Flagged'); //将星标的邮件，标志成已读 同时 为已记录邮件标记星标
            }

            $close_status = $mailbox->disconnect();
            if ($close_status){
                echo '邮箱关闭';
            }else{
                echo '邮箱未关闭';
            }

        }
        //捕获异常
        catch(Exception $e)
        {
            echo '错误信息: ' .$e->getMessage();
        }

        echo '执行完毕';


    }
    //用来处理我们自己的提交模板
    public function deal_inside_mail($str){
        $position_name =  strpos($str, '姓名：');//返回
        $position_phone = strpos($str, '电话：');
        $position_message = strpos($str, '留言内容：');
        $position_from = strpos($str, '来源：');
        $position_des = strpos($str, '描述：');


        $username = str_replace("姓名：","",substr($str,$position_name, ($position_phone-$position_name))); //电话这个词的位置减去姓名词所在的位置
        $phone = str_replace("电话：","",substr($str,$position_phone, ($position_message-$position_phone)));
        $from = str_replace("来源：","",substr($str,$position_from, ($position_des-$position_from)));

        return ['username'=>$username,'phone'=>$phone,'from'=>$from,'title'=>'','data_date'=>'2000-01-01 00:00:00'];
    }
    //用来处理外来的模板
    public function deal_outside_mail($str){
        $position_title_first = strpos($str, '"');
        $position_title_second = strrpos($str, '"');


        $position_date_start = strrpos($str, '时间为：');
        $position_date_end = strrpos($str, '。请');


        // $position_phone_start = strrpos($str, '电话:');



        $username = '';
        $from = '';
        $title = str_replace('"',"",substr($str,$position_title_first, ($position_title_second-$position_title_first)));
        $data_date = str_replace('时间为：',"",substr($str,$position_date_start, ($position_date_end-$position_date_start)));
        // $phone = str_replace("电话:","",substr($str,$position_phone_start,18));
        $soisMatched = preg_match('/0?(13|14|15|18|17|16|19)[0-9]{9}/', $str, $somatches);
        if($soisMatched < 1){
            $phone = '';
        }else{
            $phone = $somatches[0];
        }
        // var_dump($soisMatched, $somatches);
        // die();
        return ['username'=>$username,'phone'=>$phone,'from'=>$from,'title'=>$title,'data_date'=>$data_date];
    }



}
