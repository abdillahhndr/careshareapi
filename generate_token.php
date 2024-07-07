<?php
include "RtcTokenBuilder.php";

// Gantikan dengan App ID dan App Certificate dari proyek Agora Anda
$APP_ID = "YOUR_AGORA_APP_ID";
$APP_CERTIFICATE = "YOUR_AGORA_APP_CERTIFICATE";
$CHANNEL_NAME = "testChannel";
$UID = 0; // Biarkan 0 untuk membiarkan Agora menetapkan UID secara otomatis
$EXPIRE_TIME_IN_SECONDS = 3600; // Token berlaku selama 1 jam
$ROLE = RtcTokenBuilder::RolePublisher;

$timestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
$privilegeExpiredTs = $timestamp + $EXPIRE_TIME_IN_SECONDS;

$token = RtcTokenBuilder::buildTokenWithUid($APP_ID, $APP_CERTIFICATE, $CHANNEL_NAME, $UID, $ROLE, $privilegeExpiredTs);

echo json_encode(array('token' => $token));
