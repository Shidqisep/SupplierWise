    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SuppleWise | Supply Chain Intelligence</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "error-container": "#ffdad6",
                        "surface-bright": "#f8f9ff",
                        "secondary-fixed-dim": "#bcc7de",
                        "on-secondary-fixed": "#111c2d",
                        "tertiary-container": "#0057bc",
                        "surface-container-high": "#dce9ff",
                        "on-tertiary": "#ffffff",
                        "on-primary-container": "#91e5bc",
                        "on-secondary-fixed-variant": "#3c475a",
                        "on-surface": "#0b1c30",
                        "inverse-surface": "#213145",
                        "tertiary-fixed-dim": "#adc6ff",
                        "on-primary-fixed": "#002114",
                        "on-tertiary-fixed-variant": "#004395",
                        "on-error-container": "#93000a",
                        "tertiary": "#00418f",
                        "on-tertiary-container": "#c2d3ff",
                        "surface-container-lowest": "#ffffff",
                        "outline": "#6f7a72",
                        "surface-container-low": "#eff4ff",
                        "secondary": "#545f73",
                        "error": "#ba1a1a",
                        "primary-fixed-dim": "#83d7ae",
                        "outline-variant": "#bec9c1",
                        "on-primary": "#ffffff",
                        "primary": "#004f35",
                        "on-error": "#ffffff",
                        "on-surface-variant": "#3f4943",
                        "on-secondary": "#ffffff",
                        "surface": "#f8f9ff",
                        "inverse-primary": "#83d7ae",
                        "primary-fixed": "#9ff4ca",
                        "secondary-fixed": "#d8e3fb",
                        "on-tertiary-fixed": "#001a42",
                        "surface-tint": "#076c4b",
                        "surface-bg": "#f8f9ff",
                        "on-background": "#0b1c30",
                        "secondary-container": "#d5e0f8",
                        "on-primary-fixed-variant": "#005237",
                        "surface-container-highest": "#d3e4fe",
                        "outline-subtle": "rgba(109, 122, 114, 0.1)",
                        "primary-container": "#006948",
                        "inverse-on-surface": "#eaf1ff",
                        "on-secondary-container": "#586377",
                        "surface-dim": "#cbdbf5",
                        "surface-container": "#e5eeff",
                        "background": "#f8f9ff",
                        "tertiary-fixed": "#d8e2ff",
                        "surface-variant": "#d3e4fe"
                    },
                    borderRadius: {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "md": "16px",
                        "container-margin": "20px",
                        "xs": "4px",
                        "sm": "8px",
                        "xl": "32px",
                        "gutter": "16px",
                        "lg": "24px"
                    },
                    fontFamily: {
                        "headline-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "display-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-md": ["Inter"]
                    },
                    fontSize: {
                        "headline-sm": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                        "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "display-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "headline-md": ["24px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "label-md": ["12px", { lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600" }]
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
