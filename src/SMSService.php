<?php

namespace Serengiy\SendPulse;

final class SMSService extends SendPulseAbstract
{
    /**
     * Sends an SMS message with the specified payload.
     *
     * @param array{
     *     sender: string,
     *     phones: string[],
     *     body: string,
     *     date?: string,
     *     route?: array,
     *     emulate?: bool,
     *     stat_link_tracking?: bool,
     *     stat_link_need_protocol?: bool
     *   } $payload The payload data for sending an SMS message.
     *
     * @return array{
     *     result: bool,
     *     campaign_id: int,
     *     counters: array{
     *         exceptions: int,
     *         sends: int
     *     }
     *   }
     *
     * @throws \Exception If the request fails.
     */
    public function sendMessage(array $payload): array
    {
        return $this->post('sms/send', $payload);
    }
}
