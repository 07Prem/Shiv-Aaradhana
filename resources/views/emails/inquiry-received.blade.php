<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inquiry Notification - Shiv Aaradhana</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; margin: 0; padding: 24px; color: #222;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e5e5;">
        <tr style="background-color: #091433;">
            <td style="padding: 24px 32px; text-align: left;">
                <h1 style="color: #EBD6B4; margin: 0; font-size: 22px; font-weight: bold; letter-spacing: 0.5px;">SHIV AARADHANA PRIVATE LIMITED</h1>
                <p style="color: #ffffff; margin: 4px 0 0; font-size: 13px; opacity: 0.9;">International Import-Export & Sourcing Operations</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 32px;">
                <div style="display: inline-block; padding: 4px 12px; background: #EBD6B4; color: #091433; font-weight: bold; font-size: 12px; border-radius: 4px; margin-bottom: 16px;">
                    {{ strtoupper($inquiry->inquiry_type) }} REQUEST — REF: {{ $inquiry->reference_no }}
                </div>

                <h2 style="font-size: 18px; color: #091433; margin: 0 0 16px;">A new business inquiry has been recorded</h2>

                <table width="100%" cellpadding="8" cellspacing="0" style="font-size: 14px; border-collapse: collapse; margin-bottom: 24px;">
                    <tr style="border-bottom: 1px solid #eee;">
                        <td width="35%" style="color: #666; font-weight: bold;">Buyer / Sender:</td>
                        <td style="color: #111;">{{ $inquiry->full_name }}</td>
                    </tr>
                    @if($inquiry->company_name)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Company:</td>
                        <td style="color: #111;">{{ $inquiry->company_name }}</td>
                    </tr>
                    @endif
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Email:</td>
                        <td style="color: #111;"><a href="mailto:{{ $inquiry->email }}" style="color: #9C451B;">{{ $inquiry->email }}</a></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Phone / WhatsApp:</td>
                        <td style="color: #111;"><a href="tel:{{ $inquiry->phone }}" style="color: #091433;">{{ $inquiry->phone }}</a></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Country of Destination:</td>
                        <td style="color: #111;">{{ $inquiry->country }}</td>
                    </tr>
                    @if($inquiry->product)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Referenced Product:</td>
                        <td style="color: #111; font-weight: bold;">{{ $inquiry->product->name }} (HS Code: {{ $inquiry->product->hs_code ?? 'N/A' }})</td>
                    </tr>
                    @endif
                    @if($inquiry->target_quantity)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Estimated Quantity:</td>
                        <td style="color: #111;">{{ $inquiry->target_quantity }}</td>
                    </tr>
                    @endif
                    @if($inquiry->packaging_requirements)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Packaging Preference:</td>
                        <td style="color: #111;">{{ $inquiry->packaging_requirements }}</td>
                    </tr>
                    @endif
                    @if($inquiry->port_of_destination)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="color: #666; font-weight: bold;">Discharge Port:</td>
                        <td style="color: #111;">{{ $inquiry->port_of_destination }}</td>
                    </tr>
                    @endif
                </table>

                <div style="background: #fdfbf7; border-left: 4px solid #9C451B; padding: 16px; border-radius: 4px; margin-bottom: 24px;">
                    <p style="margin: 0 0 6px; font-size: 13px; font-weight: bold; color: #9C451B;">Message & Inquired Specifications:</p>
                    <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #333; white-space: pre-wrap;">{{ $inquiry->message }}</p>
                </div>

                <p style="font-size: 13px; color: #777; margin: 0;">
                    Received at: {{ $inquiry->created_at->format('d M Y, H:i') }} UTC | IP: {{ $inquiry->ip_address ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr style="background-color: #fafafa; border-top: 1px solid #eeeeee;">
            <td style="padding: 16px 32px; font-size: 12px; color: #888; text-align: center;">
                Shiv Aaradhana Private Limited &bull; Rajkot-Jamnagar Highway, Gujarat 360110, India<br>
                Tel: +91 84878 78721 &bull; Email: info.shivaaradhana@gmail.com
            </td>
        </tr>
    </table>
</body>
</html>
