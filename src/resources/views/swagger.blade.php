<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lumen Sample E-commerce API</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@3.34.0/swagger-ui.css">
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body
        {
            margin:0;
            background: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@3.34.0/swagger-ui-standalone-preset.js"></script>
<script src="https://unpkg.com/swagger-ui-dist@3.34.0/swagger-ui-bundle.js"></script>
<script>
    window.onload = function() {
        // Begin Swagger UI call region
        console.log(window.location.pathname);
        const ui = SwaggerUIBundle({
            url: "/v1/docs",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            layout: "StandaloneLayout"
        })
        // End Swagger UI call region
        window.ui = ui
    }
</script>
</body>
</html>
