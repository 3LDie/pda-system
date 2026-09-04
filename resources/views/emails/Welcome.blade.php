<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to PDA</title>
</head>
<body style="font-family: sans-serif; background-color: #f4f5f7; padding: 40px; margin: 0;">
    <div style="max-width: 600px; background-color: #ffffff; margin: 0 auto; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden;">
        
        <!-- Header Banner -->
        <div style="background-color: #7e22ce; padding: 30px; text-align: center; color: #ffffff;">
            <!-- PDA Logo Image with fallback or absolute asset URL -->
            <img src="{{ $message->embed(public_path('images/pda_logo.jpg')) }}" alt="PDA Logo" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-bottom: 12px; background-color: #ffffff; padding: 2px; display: inline-block;">
            <h1 style="margin: 0; font-size: 24px;">Welcome to the PDA Member Portal</h1>
        </div>

        <!-- Body -->
        <div style="padding: 40px; color: #333333; line-height: 1.6;">
            <p style="font-size: 16px; margin-top: 0;">Hello <strong>{{ $user->full_name ?? $user->name }}</strong>,</p>
            <p>An official administrator has registered your credentials into the local PDA Chapter Directory System. You can now log into your Member Portal to track your dynamic membership registration logs and fiscal statuses.</p>
            
            <!-- Credentials Box -->
            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; margin: 25px 0;">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #666666;"><strong>Your Sign-In Account Access Details:</strong></p>
                <p style="margin: 5px 0;"><strong>Email Address:</strong> <span style="color: #7e22ce;">{{ $user->email }}</span></p>
                <p style="margin: 5px 0;"><strong>Temporary Password:</strong> <code style="background-color: #f3e8ff; color: #6b21a8; padding: 2px 6px; border-radius: 4px; font-weight: bold;">{{ $temporaryPassword }}</code></p>
            </div>

            <p style="color: #ef4444; font-size: 13px; font-style: italic;">* Security reminder: Please navigate straight to your profile configuration right after logging in to change this temporary password to a secure personal one.</p>

            <!-- Action Button with Restored Login Link -->
            <div style="text-align: center; margin-top: 35px;">
                <a href="{{ route('login') }}" style="background-color: #7e22ce; color: #ffffff; text-decoration: none; padding: 12px 30px; font-weight: bold; border-radius: 6px; display: inline-block; box-shadow: 0 2px 4px rgba(126, 34, 206, 0.2);">
                    Log In to My Portal
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6;">
            &copy; {{ date('Y') }} Philippine Dental Association Baguio City Chapter System. All rights reserved.
        </div>
    </div>
</body>
</html>