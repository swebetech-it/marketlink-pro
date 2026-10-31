<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\EscrowTransaction;
use App\Models\User;
use App\Models\UssdSession;

class UssdService
{
    /**
     * Process incoming Africa's Talking USSD request
     * Parameters: sessionId, phoneNumber, text, networkCode, serviceCode
     * Returns standard "CON <message>" or "END <message>"
     */
    public function handleUssd(string $sessionId, string $phoneNumber, ?string $text = ''): string
    {
        $text = $text ?? '';

        $session = UssdSession::firstOrCreate(
            ['session_id' => $sessionId],
            ['phone_number' => $phoneNumber, 'menu_level' => 0]
        );

        $inputs = explode('*', trim($text));

        if ($text === "") {
            // Level 0: Main Menu
            $response  = "CON === Linkora (Swebetech) ===\n";
            $response .= "1. Angalia Fursa za Kazi\n";
            $response .= "2. Salio la Escrow\n";
            $response .= "3. Uhakiki wa NIDA ID\n";
            $response .= "4. Msaada wa Swebetech";
            return $response;
        }

        // Parse flow
        switch ($inputs[0]) {
            case "1":
                if (count($inputs) == 1) {
                    $listings = Listing::where('status', 'ACTIVE')->orderBy('is_featured', 'desc')->take(3)->get();
                    $response = "CON Fursa za Kazi Hivi Karibuni:\n";
                    $i = 1;
                    foreach ($listings as $l) {
                        $comp = substr($l->compensation, 0, 18);
                        $response .= "{$i}. {$l->title} ({$comp})\n";
                        $i++;
                    }
                    $response .= "0. Rudi Nyuma";
                    return $response;
                } else {
                    return "END Maombi yako yamepokelewa. Tutakutumia ujumbe mfupi (SMS) kupitia {$phoneNumber}. Asante!";
                }

            case "2":
                $tx = EscrowTransaction::where('escrow_status', 'HELD_IN_ESCROW')->first();
                if ($tx) {
                    $amt = number_format($tx->amount_tzs);
                    return "END Salio Lako la Escrow:\nTZS {$amt} Imeshikiliwa kwa '{$tx->listing_title}'. Audit Code: {$tx->audit_code}.";
                }
                return "END Huna malipo yanayosubiri katika Escrow kwa sasa.";

            case "3":
                if (count($inputs) == 1) {
                    return "CON Weka Nambari yako ya NIDA (tarakimu 20):\nMfano: 19940812211040000126";
                } else {
                    $nida = $inputs[1];
                    return "END Nambari ya NIDA [{$nida}] imepokelewa kwa ajili ya uhakiki. Utapata taarifa ya SMS.";
                }

            case "4":
                return "END Swebetech Company Limited\nMbeya, Tanzania\nSimu: +255 754 892 104\nWeb: swebetech.co.tz";

            default:
                return "END Chaguo si sahihi. Tafadhali piga tena *150*88#.";
        }
    }
}
