<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: GoogleAuth.proto

namespace App\Protobuf\GPBMetadata;

class GoogleAuth
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
GoogleAuth.protoapp.Protobuf.GoogleAuth"�
PayloadF
otp_parameters (2..app.Protobuf.GoogleAuth.Payload.OtpParameters
version (

batch_size (
batch_index (H �
batch_id (H��
OtpParameters
secret (
name (	
issuer (	K
	algorithm (28.app.Protobuf.GoogleAuth.Payload.OtpParameters.AlgorithmI
digits (29.app.Protobuf.GoogleAuth.Payload.OtpParameters.DigitCountD
type (26.app.Protobuf.GoogleAuth.Payload.OtpParameters.OtpType
counter (H �
period (	H�"y
	Algorithm
ALGORITHM_UNSPECIFIED 
ALGORITHM_SHA1
ALGORITHM_SHA256
ALGORITHM_SHA512
ALGORITHM_MD5"U

DigitCount
DIGIT_COUNT_UNSPECIFIED 
DIGIT_COUNT_SIX
DIGIT_COUNT_EIGHT"I
OtpType
OTP_TYPE_UNSPECIFIED 
OTP_TYPE_HOTP
OTP_TYPE_TOTPB

_counterB	
_periodB
_batch_indexB
	_batch_idB�App\\Protobuf\\GPBMetadatabproto3'
        , true);

        static::$is_initialized = true;
    }
}

