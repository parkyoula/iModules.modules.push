<?php
/**
 * 이 파일은 아이모듈 알림모듈의 일부입니다. (https://www.imodules.io)
 *
 * 알림모듈과 데이터교환을 위한 규약클래스를 정의한다.
 *
 * @file /modules/push/classes/Protocol.php
 * @author Arzz <arzz@arzz.com>
 * @license MIT License
 * @modified 2024. 10. 14.
 */
namespace modules\push;
class Protocol extends \Protocol
{
    /**
     * 전체 알림종류를 가져온다.
     *
     * @return \modules\push\dtos\Code[] $codes
     */
    public function getCodes(): array
    {
        return [];
    }

    /**
     * 회원 및 알림종류별 기본 알림수신채널을 가져온다.
     *
     * @param int $member_id 기본설정을 가져올 회원고유값 (0 인 경우 비회원으로 기본 설정 사용)
     * @param string $code 알림종류
     * @return string[] $channels 수신채널 (WEB, SMS, EMAIL)
     */
    public function getChannels(int $member_id, string $code): array
    {
        if ($member_id == 0) {
            return ['SMS', 'EMAIL'];
        } else {
            return ['WEB'];
        }
    }

    /**
     * SMS를 발송하기 위한 SMS 전송자 클래스를 가져온다.
     *
     * @param \modules\push\dtos\Message $message 알림메시지객체
     * @param ?string $name 수신자명
     * @param ?string $cellphone 수신자번호
     * @param ?\modules\sms\Sender $sender SMS 전송자 객체 (NULL 인 경우 SMS를 발송하지 않는다.)
     */
    public function getSMS(\modules\push\dtos\Message $message, ?string $name, ?string $cellphone): ?\modules\sms\Sender
    {
        return null;
    }

    /**
     * 이메일을 발송하기 위한 이메일 전송자 클래스를 가져온다.
     *
     * @param \modules\push\dtos\Message $message 알림메시지객체
     * @param ?string $name 수신자명
     * @param ?string $email 수신자메일주소
     * @return ?\modules\email\Sender $sender 이메일 전송자 객체 (NULL 인 경우 이메일을 발송하지 않는다.)
     */
    public function getEmail(\modules\push\dtos\Message $message, ?string $name, ?string $email): ?\modules\email\Sender
    {
        return null;
    }
}
