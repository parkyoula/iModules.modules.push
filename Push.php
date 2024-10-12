<?php
/**
 * 이 파일은 아이모듈 알림모듈의 일부입니다. (https://www.imodules.io)
 *
 * 알림모듈 클래스 정의한다.
 *
 * @file /modules/push/Push.php
 * @author Arzz <arzz@arzz.com>
 * @license MIT License
 * @modified 2024. 10. 6.
 */
namespace modules\push;
class Push extends \Module
{
    /**
     * @var \modules\push\Protocol $_protocol 기본 규약 클래스
     */
    private static \modules\push\Protocol $_protocol;

    /**
     * 모듈을 설정을 초기화한다.
     */
    public function init(): void
    {
    }

    /**
     * 알림을 전송하기 위한 전송자 클래스를 가져온다.
     *
     * @param \Component $component 알림을 전송하는 컴포넌트 객체
     * @param int $sended_by 알림을 전송하는 회원고유값 (0 인 경우 시스템발송으로 처리)
     * @return \modules\push\Sender $sender
     */
    public function getSender(\Component $component, int $sended_by = 0): \modules\push\Sender
    {
        return new \modules\push\Sender($component, $sended_by);
    }

    /**
     * 특정모듈의 알림 규약 클래스를 가져온다.
     * 해당 클래스가 존재하지 않을 경우 알림모듈의 기본 규약 클래스를 가져온다.
     *
     * @param \Component $target 규약 클래스를 가져올 컴포넌트 객체
     * @return \modules\push\Protocol $protocol
     */
    public function getProtocol(\Component $target): \modules\push\Protocol
    {
        $protocol = parent::getProtocol($target);

        if ($protocol === null) {
            if (isset(self::$_protocol) == false) {
                self::$_protocol = new \modules\push\Protocol($this, $this);
            }

            return self::$_protocol;
        } else {
            return $protocol;
        }
    }

    /**
     * 회원설정에 따른 알림수신채널을 가져온다.
     *
     * @param int $member_id 설정을 가져올 회원고유값 (0 인 경우 비회원으로 기본 설정 사용)
     * @param \Component $component 알림을 보내는 컴포넌트 객체
     * @param string $code 알림종류
     * @return string[] $channels 수신채널 (WEB, SMS, EMAIL)
     */
    public function getChannels(int $member_id, \Component $component, string $code): array
    {
        if ($member_id == 0) {
            $setting = null;
        } else {
            $setting = $this->db()
                ->select()
                ->from($this->table('settings'))
                ->where('member_id', $member_id)
                ->where('component_type', $component->getType())
                ->where('component_name', $component->getName())
                ->where('code', $code)
                ->getOne();
        }

        if ($setting === null) {
            return $this->getProtocol($component)->getChannels($member_id, $code);
        } else {
            $channels = [];
            if ($setting->web == 'TRUE') {
                $channels[] = 'WEB';
            }
            if ($setting->sms == 'TRUE') {
                $channels[] = 'SMS';
            }
            if ($setting->email == 'TRUE') {
                $channels[] = 'EMAIL';
            }

            return $channels;
        }
    }
}
