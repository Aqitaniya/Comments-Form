<?php

class ControllerCommentsClass{

    private $defaultData=array();
    private $commentData=array();
    private $ModelComments;

    function __construct($defaultData){
         $this->defaultData = $defaultData;

         $this->ModelComments = new ModelCommentClass(array_slice ($this->defaultData, 0, 5));
         $this->load_comments();
         $this->folder_create();
         $this->file_exist();
         $this->add_comment();
    }

    private function folder_create(){
        if(!is_dir($this->defaultData['dir']))
            mkdir($this->defaultData['dir']);
    }

    private function add_comment(){
        if(isset($_POST['commentSubmit'])){
            if($this->validation_comment()){
                if(!empty($this->commentData['filename'])){
                     if($this->load_file()){
                         $this->save_comment();
                     }
                }else{
                     $this->save_comment();
                }
            }
            else
                echo '{"status":0,"errors":'.json_encode($this->commentData).'}';
        }
    }
    private function save_comment(){
         $id=$this->ModelComments->insert_records($this->commentData);
         if($id){
              $comment=$this->load_last_comment($id);
              $this->send_mail();
              echo json_encode(array('status'=>1,'html'=>$comment));
         }
    }
    private function validation_comment(){
        $errors = array();
        $this->commentData = array();
        $this->commentData['filename']=$_POST['filename'];

        if(!($this->commentData['email'] = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)))
            $errors['email'] = 'Please enter a valid email address';
        if(!($this->commentData['fullname'] = filter_input(INPUT_POST,'fullname',FILTER_CALLBACK, array('options'=>'self::validate_text'))))
            $errors['fullname'] = 'Please enter your fullname';
        if(!($this->commentData['fullname'] = filter_input(INPUT_POST,'fullname',FILTER_CALLBACK, array('options'=>'self::validate_fullname'))))
            $errors['fullname'] = 'Your Full Name must consist of at least 6 character.';
        if(!($this->commentData['phone'] = filter_input(INPUT_POST,'phone',FILTER_CALLBACK,array('options'=>'self::validate_text'))))
            $errors['phone'] = 'Please enter the phone in the format +XXXXXXXXXXXX';
        if(!($this->commentData['phone'] = filter_input(INPUT_POST,'phone',FILTER_CALLBACK,array('options'=>'self::validate_phone'))))
            $errors['phone'] = 'Your phone must consist of at least 3 character';
        if(!($this->commentData['phone'] = filter_input(INPUT_POST,'phone',FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/[+]?[0-9]+$/")))))
            $errors['phone'] = 'Please enter your phone in the correct format';
        if(!($this->commentData['comment'] = filter_input(INPUT_POST,'comment',FILTER_CALLBACK, array('options'=>'self::validate_text'))))
            $errors['comment'] = 'Please enter your comment';
        if(!empty($this->commentData['filename'])&& file_exists($this->defaultData['dir'].'/'. $this->commentData['filename']))
             $errors['filename'] = 'File with this name has already been loaded';
        if(!empty($errors)){
             $this->commentData = $errors;
             return false;
        }
        foreach($this->commentData as $key=>$value){
             $this->commentData[$key] = mysql_real_escape_string($value);
        }
        $this->commentData['email'] = strtolower(trim($this->commentData['email']));
        return true;
    }
    private function validate_text($value)
    {
    	if(mb_strlen($value,'utf8')<1)
    		return false;
    	$value = nl2br(htmlspecialchars($value));

    	$value = str_replace(array(chr(10),chr(13)),'',$value);

    	return $value;
    }
    private function validate_phone($value)
    {
    	if(mb_strlen($value,'utf8')<3)
    		return false;
    	else
    	    return $value;
    }
    private function validate_fullname($value)
    {
       	if(mb_strlen($value,'utf8')<6)
       		return false;
       	else
       	    return $value;
    }
    private function load_file()
    {
         $uploadfile = $this->defaultData['dir'] .'/'. basename($_FILES['userfile']['name']);
              if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
                   return true;
              }else{
                   echo "Possible attack via file upload!\n";
                   return false;
              }
    }
    private function file_exist(){
        if(isset($_POST['fileExist'])){
            if (file_exists($this->defaultData['dir'].'/'.$_POST['fileExist']))
                 echo true;
            else
                echo false;
        }
    }
    private function send_mail(){
         $thema = "Your comment on the site Comments";
         $msg = "The content of the comment:".$this->commentData['comment'];
         $mail_to = $this->commentData['email'];
         $name = $this->commentData['filename'];
         $path = $this->defaultData['dir'].'/'. $this->commentData['filename'];

         $EOL = "\r\n";
         $boundary     = "--".md5(uniqid(time()));
         $headers    = "MIME-Version: 1.0;$EOL";
         $headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
         $headers   .= "From: anastasiia.rashavchenko@gmail.com";

         $multipart  = "--$boundary$EOL";
         $multipart .= "Content-Type: text/html; charset=windows-1251$EOL";
         $multipart .= "Content-Transfer-Encoding: base64$EOL";
         $multipart .= $EOL;
         $multipart .= chunk_split(base64_encode($msg));
         $multipart .=  "$EOL--$boundary$EOL";

         if ($path && !empty($this->commentData['filename'])) {
              $fp = fopen($path,"rb");
              if (!$fp){
                    print "Cannot open file";
                    exit();
              }
              $file = fread($fp, filesize($path));
              fclose($fp);


              $multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";
              $multipart .= "Content-Transfer-Encoding: base64$EOL";
              $multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";
              $multipart .= $EOL;
              $multipart .= chunk_split(base64_encode($file));
              $multipart .= "$EOL--$boundary--$EOL";
         }

         if(!mail($mail_to, $thema, $multipart, $headers))
              return False;
         else
              return true;
         exit;
    }

    function load_comments(){
         $array_comments = $this->ModelComments->get_comments();
         $comments='';
         for ($row_no = $array_comments->num_rows - 1; $row_no >= 0; $row_no--) {
              $array_comments->data_seek($row_no);
              $row = $array_comments->fetch_assoc();
              $comments .= $this->get_comment_view($row);
         }
              return $comments;
    }

    function load_last_comment($id){
         $comment = $this->ModelComments->get_last_comment($id);
         $row = $comment->fetch_assoc();
         return $this->get_comment_view($row);
    }

    private function get_comment_view($comment){
         $comment['date']=strtotime($comment['date']);
         return '<div class="comment-data">
                    <div class="comment-info">
                         <div class="comment-info-meta">
                             <span class="comment-id-title">Comment id :</span>
                             <span class="comment-id">'.$comment['id'].'</span>&nbsp;
                             <span class="comment-date">'.date('d M Y',$comment['date']).'</span>
                         </div>
                         <div class="comment-info-author">
                             <span class = "author-fullName-title">Author:</span>
                                 <span class = "author-fullName">'.$comment['fullname'].'</span>
                                 <span class="author-email-content">
                                 <span class = "author-email-title">email:</span>
                                 <span class = "author-email">'.$comment['email'].'</span>
                             </span>
                         </div>
                    </div>
                    <div class="comment-content">
                         <p>'.$comment['comment'].'</p>
                    </div>
                 </div>';
    }
}
?>