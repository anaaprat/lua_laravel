<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - {{ auth()->user()->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .page-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .page-header .logo {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background-color: var(--dark-sidebar);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-header .logo i {
            color: white;
            font-size: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
        }

        .account-tabs {
            display: flex;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .account-tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .account-tab.active {
            background-color: var(--primary);
        }

        .account-tab:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .account-tab i {
            margin-right: 8px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .account-section {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-md);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
        }

        .section-header i {
            color: var(--primary-light);
            font-size: 1.2rem;
        }

        .form-row {
            margin-bottom: 1.2rem;
        }

        .form-row label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: white;
        }

        .form-row input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-md);
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-row input:focus {
            outline: none;
            border-color: var(--primary);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .error-message {
            color: #ffb3b3;
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }

        .success-message {
            background-color: rgba(16, 185, 129, 0.2);
            color:rgb(247, 251, 249);
            padding: 0.8rem 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 1.5rem;
        }

        .qr-image {
            width: 250px;
            height: 250px;
            background-color: white;
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
        }

        .qr-help-text {
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            max-width: 400px;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--primary-dark);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            margin-top: 1rem;
            transition: var(--transition);
        }

        .download-btn:hover {
            background-color: var(--primary);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            main {
                padding: 1.5rem;
            }

            .account-tabs {
                flex-direction: column;
            }

            .account-tab {
                padding: 0.8rem;
            }
        }
    </style>
</head>

<body>
    @include('bar.side-bar')

    <main>
        <div class="page-header">
            <div class="logo">
                <i class="fas fa-user-cog"></i>
            </div>
            <h1>My Account</h1>
        </div>

        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="account-tabs">
            <div class="account-tab active" data-tab="profile">
                <i class="fas fa-user"></i> Profile
            </div>
            <div class="account-tab" data-tab="security">
                <i class="fas fa-lock"></i> Security
            </div>
            <div class="account-tab" data-tab="qr">
                <i class="fas fa-qrcode"></i> QR Code
            </div>
        </div>

        <div class="tab-content active" id="profile-tab">
            <div class="account-section">
                <div class="section-header">
                    <i class="fas fa-user-edit"></i>
                    <h2>Bar Information</h2>
                </div>
                <form action="{{ route('bar.account.update') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <label for="name">Bar Name</label>
                        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label for="table_number">Number of Tables</label>
                        <input type="number" id="table_number" name="table_number"
                            value="{{ auth()->user()->table_number ?? 0 }}" min="1" max="100" required>
                        <small style="color: rgba(255, 255, 255, 0.7); display: block; margin-top: 5px;">
                            This number defines how many tables will be available for your customers.
                        </small>
                        @error('table_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <div class="tab-content" id="security-tab">
            <div class="account-section">
                <div class="section-header">
                    <i class="fas fa-shield-alt"></i>
                    <h2>Change Password</h2>
                </div>
                <form action="{{ route('bar.account.updatePassword') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required>
                        <small style="color: rgba(255, 255, 255, 0.7); display: block; margin-top: 5px;">
                            Must be at least 6 characters long.
                        </small>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        <div class="tab-content" id="qr-tab">
            <div class="account-section">
                <div class="section-header">
                    <i class="fas fa-qrcode"></i>
                    <h2>Your Bar's QR Code</h2>
                </div>

                @if(auth()->user()->qr_path)
                    <div class="qr-section">
                        <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" class="qr-image" alt="QR Code">
                        <p class="qr-help-text">
                            This QR code is unique to your bar. Place it on your tables or at the entrance so customers
                            can scan it with the Lua app and connect to your establishment.
                        </p>
                        <a href="{{ asset('storage/' . auth()->user()->qr_path) }}"
                            download="qr_{{ auth()->user()->name }}.svg" class="download-btn">
                            <i class="fas fa-download"></i> Download QR Code
                        </a>
                    </div>
                @else
                    <div class="qr-section">
                        <p style="color: white; text-align: center;">
                            No QR code has been generated for your bar yet.
                            Contact the administrator to resolve this issue.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.account-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(tc => tc.classList.remove('active'));

                    this.classList.add('active');

                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId + '-tab').classList.add('active');
                });
            });
        });
    </script>
</body>

</html>