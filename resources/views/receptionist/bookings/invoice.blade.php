<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $booking->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 16px; line-height: 24px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td{ border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                Hotel BSI
                            </td>
                            <td>
                                Invoice #: {{ $booking->id }}<br>
                                Dibuat: {{ $booking->transaction->transaction_date->format('d F Y') }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Ditagihkan Kepada:</strong><br>
                                {{ $booking->guest->name }}<br>
                                {{ $booking->guest->phone_number }}<br>
                                {{ $booking->guest->email }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Deskripsi</td>
                <td>Harga</td>
            </tr>
            <tr class="item">
                <td>
                    Menginap di Kamar {{ $booking->room->room_number }} ({{ $booking->room->type }})<br>
                    <strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}<br>
                    <strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}
                </td>
                <td class="text-right">Rp {{ number_format($booking->room->price_per_night, 0, ',', '.') }} / malam</td>
            </tr>
            <tr class="details">
                <td>
                    <strong>Jumlah Malam:</strong> {{ $nights }}
                </td>
                <td class="text-right"></td>
            </tr>
            <tr class="total">
                <td></td>
                <td>
                   <strong>Total: Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </td>
        </table>
        <br>
        <p style="text-align:center;">Terima kasih telah menginap di Hotel Anda!</p>
    </div>
</body>
</html>
