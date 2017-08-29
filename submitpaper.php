<?php
require_once 'http.php';
require_once 'curl.php';
require_once 'config.php';
// 设置输出编码
header('Content-Type:text/html;charset=utf-8');
function submitPaper($userid) {
    global $host;
    global $headers;
    global $page_index;
    global $page_entercarlist;
    global $page_loadotherdrivers;
    global $page_submitpaper;
    global $appsource;

    // usr.json读取
    $user_info = loadConfig($userid.'/'.'user.json');
    $platform = $user_info['platform'];

    // car.json中读取车辆信息
    $car_info = loadConfig($userid.'/'.'car.json');
    $licenseno = $car_info['licenseno'];
    $engineno = $car_info['engineno'];
    $cartypecode = $car_info['cartypecode'];
    $vehicletype = $car_info['vehicletype'];

    $carid = $car_info['carid'];
    $carmodel = $car_info['carmodel'];
    $carregtime = $car_info['carregtime'];

    $envGrade = $car_info['envGrade'];

    // 其余无用的信息
    $imei = '';
    $imsi = '';
    $gpslon = '';
    $gpslat = '';
    $phoneno = '';
    $code = '';

    // 从person.json中读取个人信息
    $person_info = loadConfig($userid.'/'.'person.json');
    $drivingphoto = $person_info['drivingphoto'];
    $carphoto = $person_info['carphoto'];
    $drivername = $person_info['drivername'];
    $driverlicenseno = $person_info['driverlicenseno'];
    $driverphoto = $person_info['driverphoto'];
    $personphoto = $person_info['personphoto'];
    // 进京时间选择
    $inbjentrancecode1 = '16';
    $inbjentrancecode = '13';
    $inbjduration = '7';
    // 进京时间，如果存在进京证，则从明天开始，否则是今日开始
    $inbjtime = date("Y-m-d");
    // 默认申请明天的
    {
        $tomorrow = date_create($inbjtime);
        date_add($tomorrow, date_interval_create_from_date_string("1 days"));
        $inbjtime = date_format($tomorrow,"Y-m-d");
    }
    // 对时间戳取整
    $hiddentime = makeTimestampPoint();
    $date = date("Y-m-d", strtotime($hiddentime));

    // var imageId = $("#inbjentrancecode").val()+$("#inbjduration").val()+$("#inbjtime").val()+$("#userid").val()+$("#engineno").val()+$("#cartypecode").val()+$("#driverlicensenow").val()+$("#carid").val()+timestamp;
    $imageId = $inbjentrancecode.$inbjduration.$inbjtime.$userid.$engineno.$cartypecode.$driverlicenseno.$carid.$hiddentime;

    // imageId取sign
    $json_timestamp = loadConfig($userid.'/'.$date.'/'.'timestamp.json');
    // sign从json中获取
    $sign = $json_timestamp[$hiddentime];

    $form = array(
        'appsource'=>$appsource,
        'hiddentime'=>$hiddentime,
        'inbjentrancecode1'=>$inbjentrancecode1,
        'inbjentrancecode'=>$inbjentrancecode,
        'inbjduration'=>$inbjduration,
        'inbjtime'=>$inbjtime,
        'appkey'=>'',
        'deviceid'=>'',
        'token'=>'',
        'timestamp'=>'',
        'userid'=>$userid,
        'licenseno'=>$licenseno,
        'engineno'=>$engineno,
        'cartypecode'=>$cartypecode,
        'vehicletype'=>$vehicletype,
        'drivingphoto'=>$drivingphoto,
        'carphoto'=>$carphoto,
        'drivername'=>$drivername,
        'driverlicenseno'=>$driverlicenseno,
        'driverphoto'=>$driverphoto,
        'personphoto'=>$personphoto,
        'gpslon'=>$gpslon,
        'gpslat'=>$gpslat,
        'phoneno'=>$phoneno,
        'imei'=>$imei,
        'imsi'=>$imsi,
        'carid'=>$carid,
        'carmodel'=>$carmodel,
        'carregtime'=>$carregtime,
        'envGrade'=>$envGrade,
        'imageId'=>$imageId,
        'code'=>$code,
        'sign'=>$sign,
        'platform'=>$platform,
    );
    return curl_post($headers, http_build_query($form), $host.$page_submitpaper, $host.$page_loadotherdrivers);
}
?>