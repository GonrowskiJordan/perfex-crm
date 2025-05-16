<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/modules/api/assets/swagger/swagger-ui.css') ?>">
    <script src="<?php echo base_url('/modules/api/assets/swagger/swagger-ui-bundle.js') ?>"></script>
    <script src="<?php echo base_url('/modules/api/assets/swagger/swagger-ui-standalone-preset.js') ?>"></script>
</head>
<body>
    <div id="swagger-ui"></div>
    <style>
        [data-param-name="check_api"] {
            display: none;
        }
    </style>
    <script>
        const HideInternalPlugin = () => {
            return {
                statePlugins: {
                    spec: {
                        wrapSelectors: {
                            parameterWithMeta: (ori) => (state, ...args) => {
                                const param = ori(state, ...args);
                                if (param && param.get("x-hidden")) return null; // 👈 Hide if internal
                                return param;
                            }
                        }
                    }
                }
            };
        };
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "<?php echo site_url('/api/playground-json'); ?>",
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIBundle.SwaggerUIStandalonePreset
                ],
                plugins: [ HideInternalPlugin ]
            });
        }
    </script>
</body>
</html>