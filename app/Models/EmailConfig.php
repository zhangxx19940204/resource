<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    //
    use HasFactory;
     /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'email_config';

    // public function emailData()
    // {
    //     return $this->belongsTo(EmailData::class,'econfig_id','id');
    // }

    //获得对应的邮件的文件夹列表
	public function get_mail_folder_list($data){
		//进行邮箱的连接，并获得邮件的文件列表
		// var_dump($data);
		$mailServer=$data->host_port; //IMAP主机

		$mailLink="{{$mailServer}}INBOX" ; //imagp连接地址：不同主机地址不同

		$mailUser = $data->email_address; //邮箱用户名

		$mailPass = $data->email_password; //邮箱密码

		$mbox = imap_open($mailLink,$mailUser,$mailPass); //开启信箱imap_open

		// $totalrows = imap_num_msg($mbox); //取得信件数
		// echo $totalrows;

		$server = "{{$mailServer}}";
		$mailboxes = imap_list($mbox, $server, '*');

		if (is_array($mailboxes)) {
			$mailboxs_arr = [];
			//转化数组的格式
			foreach ($mailboxes as $key=>$value) {
				$mailboxs_arr[$value] = $value;
			}
		    return $mailboxs_arr;
		} else {
		    return ['读取出错，联系管理员'=>'读取出错，联系管理员'];
		}

	}
}
