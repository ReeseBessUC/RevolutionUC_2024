<?php
class devShortcodes {
    private $shortcodes = array(
        'control-questions' => 'func_control_questions',
        'control-charts' => 'func_control_charts',
        'user-settings' => 'func_user_settings',
        'user-signup' => 'func_user_signup',
        'user-login' => 'func_user_login',
        'bar-chart' => 'func_bar_chart',
        'line-chart' => 'func_line_chart',
        'pie-chart' => 'func_pie_chart',
        'donut-chart' => 'func_donut_chart',
        'company-search' => 'func_company_search',
        'ai-text-generation' => 'func_ai_text_generation',
        'charts-by-dev' => 'func_charts_by_dev'

    );
    
    function __construct() {
        
        foreach ($this->shortcodes as $shortcode => $callback)
        {
            add_shortcode($shortcode, array($this, $callback));
        }
    }
    
    function func_control_questions() {
        return get_template_part('views/frm_control_questions');
    }

    function func_control_charts() {
        return get_template_part('views/control_charts');
    }

    function func_user_settings() {
        return get_template_part('views/user_settings');
    }

    function func_user_signup() {
        return get_template_part('views/signup');
    }

    function func_user_login() {
        return get_template_part('views/login');
    }

    function func_bar_chart() {
        return get_template_part('views/charts/bar');
    }

    function func_line_chart() {
        return get_template_part('views/charts/line');
    }

    function func_pie_chart() {
        return get_template_part('views/charts/pie');
    }

    function func_donut_chart() {
        return get_template_part('views/charts/donut');
    }

    function func_company_search() {
        return get_template_part('views/company_search');
    }

    function func_charts_by_dev() {
        return get_template_part('views/charts/charts_by_dev');
    }

    function func_ai_text_generation()
    {

        return get_template_part('views/ai_text_generation');

        

        

        /*
        $remote_url = 'https://api.openai.com/v1/chat/completions';
        
        $args = array(
            'headers'     => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ),
            'body' => wp_json_encode([
                "model" => "gpt-3.5-turbo",
                "max_tokens" => 256,
                "top_p" => "1",
                "frequency_penalty" => "0",
                "presence_penalty" => "0",
                "messages" => [
                    "role" => "user",
                    "content" => $prompt
                ]
            ]),
        ); 
        $result = wp_remote_post( $remote_url, $args );
        */

        echo "<pre>";
        print_r($result);
    }

}

new devShortcodes();