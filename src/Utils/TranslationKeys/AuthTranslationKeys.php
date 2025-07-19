<?php

namespace Corekit\Utils\TranslationKeys;

class AuthTranslationKeys
{
    // Credentials / Login
    public const INVALID_CREDENTIALS = 'invalid_credentials';
    public const USER_NOT_FOUND = 'user_not_found';
    public const ACCOUNT_LOCKED = 'account_locked';
    public const ACCOUNT_DISABLED = 'account_disabled';
    public const ACCOUNT_SUSPENDED = 'account_suspended';
    public const TOO_MANY_ATTEMPTS = 'too_many_attempts';

    // Tokens / JWT / Sessions
    public const TOKEN_EXPIRED = 'token_expired';
    public const TOKEN_INVALID = 'token_invalid';
    public const TOKEN_MISSING = 'token_missing';
    public const SESSION_EXPIRED = 'session_expired';
    public const UNAUTHORIZED = 'unauthorized_access';
    public const FORBIDDEN = 'forbidden_action';

    // Email / Phone Verification
    public const EMAIL_NOT_VERIFIED = 'email_not_verified';
    public const PHONE_NOT_VERIFIED = 'phone_not_verified';
    public const INVALID_EMAIL = 'invalid_email';
    public const INVALID_PHONE_NUMBER = 'invalid_phone_number';

    // OTP / Code / MFA
    public const OTP_INVALID = 'otp_invalid';
    public const OTP_EXPIRED = 'otp_expired';
    public const OTP_ATTEMPTS_EXCEEDED = 'otp_attempts_exceeded';
    public const OTP_NOT_SENT = 'otp_not_sent';
    public const OTP_NOT_REQUESTED = 'otp_not_requested';

    // Password reset
    public const PASSWORD_RESET_TOKEN_INVALID = 'password_reset_token_invalid';
    public const PASSWORD_RESET_TOKEN_EXPIRED = 'password_reset_token_expired';
    public const PASSWORD_RESET_FAILED = 'password_reset_failed';
    public const PASSWORD_TOO_WEAK = 'password_too_weak';
    public const PASSWORD_MISMATCH = 'password_mismatch';

    // Registration / Validation
    public const USER_ALREADY_EXISTS = 'user_already_exists';
    public const EMAIL_ALREADY_TAKEN = 'email_already_taken';
    public const PHONE_ALREADY_TAKEN = 'phone_already_taken';
    public const INVALID_USERNAME = 'invalid_username';
    public const INVALID_ROLE = 'invalid_role';

    // MFA / 2FA / Security
    public const MFA_REQUIRED = 'mfa_required';
    public const MFA_FAILED = 'mfa_failed';
    public const SECURITY_CHALLENGE_FAILED = 'security_challenge_failed';

    // Others
    public const ACCOUNT_NOT_ACTIVATED = 'account_not_activated';
    public const DEVICE_NOT_RECOGNIZED = 'device_not_recognized';
    public const LOCATION_BLOCKED = 'location_blocked';
    public const LOGIN_NOT_ALLOWED = 'login_not_allowed';
}