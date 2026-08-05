{{--
  Deliberately standalone.

  A 500 means something in the app is already broken. If this page pulled in
  layouts.app it would need routes, config and an external stylesheet to
  resolve — any of which may be the thing that failed, turning a handled
  error into a blank white screen. So: no @extends, no route() helpers, no
  external CSS or fonts. Plain relative links and inline styles only.
--}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something went wrong — Muhammad Ali</title>
    <style>
        :root {
            --bg: #12161a;
            --surface: #1b222a;
            --ink: #e9e6e1;
            --ink-2: #b4bac0;
            --ink-3: #8a939b;
            --rule: rgba(233, 230, 225, .13);
            --accent: #e08a4f;
            --accent-ink: #12161a;
            color-scheme: dark;
        }

        @media (prefers-color-scheme: light) {
            :root {
                --bg: #fbfaf8;
                --surface: #ffffff;
                --ink: #14181c;
                --ink-2: #454c53;
                --ink-3: #6b747c;
                --rule: rgba(20, 24, 28, .14);
                --accent: #a85a2c;
                --accent-ink: #ffffff;
                color-scheme: light;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter Tight', system-ui, -apple-system, 'Segoe UI', sans-serif;
            line-height: 1.65;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            -webkit-font-smoothing: antialiased;
        }

        .wrap {
            width: 100%;
            max-width: 540px;
        }

        .code {
            font-family: 'Iowan Old Style', Georgia, serif;
            font-size: clamp(4.5rem, 15vw, 7rem);
            font-weight: 600;
            line-height: .85;
            letter-spacing: -.05em;
            color: var(--accent);
        }

        .label {
            font-family: ui-monospace, Consolas, monospace;
            font-size: .68rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--ink-3);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--rule);
        }

        h1 {
            font-family: 'Iowan Old Style', Georgia, serif;
            font-size: clamp(1.6rem, 4.5vw, 2.2rem);
            font-weight: 600;
            letter-spacing: -.025em;
            line-height: 1.12;
            margin: 1.5rem 0 .75rem;
        }

        p {
            color: var(--ink-2);
            max-width: 48ch;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: .7rem;
            margin-top: 1.75rem;
        }

        a.btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .92rem;
            font-weight: 600;
            padding: .75rem 1.3rem;
            border-radius: 6px;
            border: 1px solid var(--rule);
            color: var(--ink);
            text-decoration: none;
            transition: transform .15s ease, background-color .2s ease;
        }

        a.btn.primary {
            background: var(--accent);
            border-color: var(--accent);
            color: var(--accent-ink);
        }

        a.btn:hover {
            transform: translateY(-1px);
        }

        .ref {
            font-family: ui-monospace, Consolas, monospace;
            font-size: .7rem;
            letter-spacing: .04em;
            color: var(--ink-3);
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--rule);
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="code">500</div>
        <div class="label">Server error</div>

        <h1>Something went wrong on my end.</h1>
        <p>
            This one is my fault, not yours. The error has been logged and I'll look into it.
            Try again in a moment — if it keeps happening, email me and I'll get it fixed.
        </p>

        <div class="actions">
            <a href="/" class="btn primary">Back to home</a>
            <a href="mailto:amarjafri1472@gmail.com" class="btn">Report it</a>
        </div>

        <div class="ref">amarjafri1472@gmail.com</div>
    </div>
</body>

</html>
