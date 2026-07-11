<x-mail::message>
# You've been invited to {{ $siteName }}

You have been invited to join **{{ $siteName }}** as **{{ $role }}**.

Click the button below to accept your invitation and set up your account. This invitation expires on **{{ $expiresAt }}**.

<x-mail::button :url="$acceptUrl">
Accept Invitation
</x-mail::button>

If you did not expect this invitation, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
