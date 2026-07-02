<?php
if ($pre_checkout_query) {
    bot('answerPreCheckoutQuery', [
        'pre_checkout_query_id' => $pre_checkout_query['id'],
        'ok' => true
    ]);
    exit;
}

if ($message && isset($message['successful_payment'])) {
    $amount = $message['successful_payment']['total_amount'];
    $chat_id = $message['chat']['id'];
    $user_id = $message['from']['id'];
    
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🤝 <b>Thanks very much for the donation.
        
Amount:</b> $amount ⭐️ Stars",
        'parse_mode' => 'HTML'
    ]);
    exit;
}

if ($message && strpos($text, '/donate ') === 0) {
    $amount = intval(trim(substr($text, 8)));
    if ($amount > 0) {
        bot('sendInvoice', [
            'chat_id' => $chat_id,
            'title' => "Donation",
            'description' => "Donation of $amount Stars",
            'payload' => "payment_$amount",
            'provider_token' => "",
            'currency' => 'XTR',
            'prices' => json_encode([['label' => 'Stars', 'amount' => $amount]])
        ]);
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "❌ Invalid amount. For example: /donate 10"
        ]);
    }
    exit;
}
?>
