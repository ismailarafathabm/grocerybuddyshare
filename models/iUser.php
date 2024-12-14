<?php 
    function UserResponse($msg,$data = [],$isSuccess = false,$statusCode = 409){
        return array(
            "isSucces" => $isSuccess,
            "msg" => $msg,
            "data" => $data,
            "statusCode" => $statusCode
        );
        exit();
    }
    function IuserInsert($_POST,$enc,$username){
        extract($_POST);
       
        $isOkUsername = !isset($userName) || trim($userName) === "" ? false : true;
        if(!$isOkUsername){
            return UserResponse("Enter user Name");
            exit();
        }
        $isOkUserId = !isset($userId) || trim($userId) === "" ? false : true ;
        if(!$isOkUserId){
            return UserResponse("Enter User ID");
            exit();
        }
        $isOkuserPass = !isset($userPass) || trim($userPass) === "" ? false : true;
        if(!$isOkuserPass){
            return UserResponse("Enter Password");exit();
            
        }
        $isOkUserType = !isset($userType) || trim($userType) === "" ? false : true;
        if(!$isOkUserType){
            return UserResponse("Enter User Type");
        }
        
        $Iuser = array(
            ":userName" => $userName,
            ":userId" => strtolower($userId),
            ":userPass" => $enc->enc('enc',$userPass),
            ":userToken" => $enc->token(25) . "_" . $enc->enc('enc',$userId) . "_" . date('YmdHi'),
            ":userIp" => getenv("REMOTE_ADDR"),
            ":userLastLogin" => date('Y-m-d H:i:s'),
            ":userType" => $userType,
            ":userStatus" => 1,
            ":userCby" => $username,
            ":userEby" => $username,
            ":userRole" => $userType
        );

        
        return UserResponse(true,"ok",$Iuser,200);
        exit();
    }
?>