<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <style>
        :root {
            color-scheme: light;
            --primary: #0f766e;
            --primary-dark: #115e59;
            --accent: #d97706;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --surface-muted: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--surface);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .error-page__container {
            width: min(1120px, 100%);
            margin: 0 auto;
            padding: 72px 24px;
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.9fr);
            gap: 40px;
            align-items: center;
        }

        .error-page__badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(217, 119, 6, 0.1);
            color: var(--accent);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .error-page__icon {
            font-size: 18px;
            line-height: 1;
        }

        .error-page__title {
            margin: 0 0 20px;
            color: var(--primary-dark);
            font-size: clamp(36px, 7vw, 64px);
            line-height: 1.05;
            font-weight: 800;
        }

        .error-page__message {
            max-width: 660px;
            margin: 0 0 32px;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.7;
        }

        .error-page__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .error-page__button {
            appearance: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 12px 18px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .error-page__button--primary {
            background: var(--primary);
            color: #fff;
        }

        .error-page__button--primary:hover {
            background: var(--primary-dark);
        }

        .error-page__button--secondary {
            background: #fff;
            color: #475569;
            border-color: var(--border);
        }

        .error-page__button--secondary:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        .error-page__panel {
            position: relative;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            background: var(--surface-muted);
            padding: 32px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }

        .error-page__watermark {
            position: absolute;
            top: 18px;
            right: 24px;
            color: rgba(15, 118, 110, 0.08);
            font-size: 112px;
            font-weight: 900;
            line-height: 1;
        }

        .error-page__symbol {
            position: relative;
            display: flex;
            width: 80px;
            height: 80px;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            border-radius: 12px;
            background: #fff;
            color: var(--primary);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
            font-size: 42px;
            font-weight: 800;
        }

        .error-page__line {
            height: 12px;
            border-radius: 999px;
            background: #e2e8f0;
            margin-bottom: 12px;
        }

        .error-page__line:nth-child(1) {
            width: 75%;
        }

        .error-page__line:nth-child(2) {
            width: 50%;
        }

        .error-page__line:nth-child(3) {
            width: 66%;
        }

        .error-page__note {
            position: relative;
            margin-top: 40px;
            padding: 16px;
            border: 1px dashed var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 800px) {
            .error-page__container {
                grid-template-columns: 1fr;
                padding: 48px 20px;
            }

            .error-page__panel {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
