<?php
class clsCompliance
{

    function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'dev_enqueue_css_js']);
        //add_action('init', [$this, 'dev_template_redirect']);
        add_action( 'template_redirect', [$this, 'dev_template_redirect'] );
        
    }

    function dev_enqueue_css_js()
    {
        wp_register_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');

        wp_register_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js', ['jquery']);
        wp_register_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', ['jquery']);
        
    }

    function getCompanies()
    {
        return get_posts([
            'post_type' => 'company',
            'post_status' => 'publish'
        ]);
    }

    function getControls()
    {
        return get_posts([
            'orderby'       => 'meta_value',
            'meta_key'      => 'sort_order',
            'order'         => 'ASC',      
            'post_type'     => 'control',
            'post_status'   => 'publish',
            'numberposts'   => -1
        ]);
    }

    function getAnswers()
    {
        return get_posts([
            'post_type'     => 'answer',
            'post_status'   => 'publish',
            'numberposts'   => 1,
            'meta_query' => array(
                array(
                    'key'   => 'user_id',
                    'value' => get_current_user_id(),
                )
            )
        ]);
    }

    function dev_template_redirect()
    {
        # check if this page is for members;
        $for_members = get_field("for_members");
        if ($for_members && !is_user_logged_in())
        {
            wp_redirect( get_permalink(82) );
            exit;
        }
        

        # User Signup form submission;
        if (isset($_POST['sbtUserSignup']))
        {
            global $post;

            $wpUserId = wp_insert_user([
                'user_pass' => $_POST['password'],
                'user_login' => $_POST['email'],
                'user_email' => $_POST['email'],
                'first_name' => $_POST['userData_Name_First'],
                'last_name' => $_POST['userData_Name_Last']
            ]);

            # create company and associate to this user;
            $company_id = wp_insert_post([
                'post_type' => 'company',
                'post_title' => $_POST['userData_Name_Business'], 
                'post_status' => 'publish'
            ]);

            $objUser = $this->checkUserLogin( $_POST['email'], $_POST['password'] );

            $objUser = json_decode($objUser);
            
            if ($objUser->success)
            {
                wp_clear_auth_cookie();
                wp_set_current_user($objUser->user->ID);
                wp_set_auth_cookie($objUser->user->ID);

                wp_redirect( add_query_arg(['loginsuccess'=>1], get_permalink(30)) );
            }
            else
            {
                wp_redirect( add_query_arg(['loginerror'=>1], get_permalink($post->ID)) );
            }
            
            exit;
        }

        # User Login form submission;
        if (isset($_POST['sbtUserLogin']))
        {
            global $post;
            $objUser = $this->checkUserLogin( $_POST['email'], $_POST['password'] );

            $objUser = json_decode($objUser);
            
            if ($objUser->success)
            {
                wp_clear_auth_cookie();
                wp_set_current_user($objUser->user->ID);
                wp_set_auth_cookie($objUser->user->ID);

                wp_redirect( add_query_arg(['loginsuccess'=>1], get_permalink(30)) );
            }
            else
            {
                wp_redirect( add_query_arg(['loginerror'=>1], get_permalink($post->ID)) );
            }
            
            exit;
        }

        if (isset($_POST['txtControlSubmit']))
        {
            global $post;

            $author_obj = get_user_by('id', $_POST['user_id']);

            $arrAnswers = $this->getAnswers();

            if (count($arrAnswers))
            {
                $post_id = $arrAnswers[0]->ID;
            }
            else
            {
                $post_id = wp_insert_post([
                    'post_type' => 'answer',
                    'post_title' => 'Answers by ' .  $author_obj->user_nicename,
                    'post_status' => 'publish'
                ]);
            }

            

            $company_id = (isset($_POST['company_id'])) ? $_POST['company_id'] : get_user_meta(get_current_user_id(), "company_id", true);
            $answers = "";

            foreach ($_POST['question'] as $control)
            {
                $control_title = "";
                $control_value = "";

                foreach ($control as $key => $value)
                {
                    $control_title = get_the_title($key);
                    $control_value = $value;
                }

                $answers .= $control_value . "=" . $control_title . "\n";
            }

            update_post_meta($post_id, "answers", $answers);
            update_post_meta($post_id, "user_id", $_POST['user_id']);
            update_post_meta($post_id, "company_id", $company_id);

            
            wp_redirect( add_query_arg([
                "success" => "1"
            ], get_permalink($post->ID)) );
            
        }

        if (isset($_POST['company_submit']))
        {
            global $post;

            update_user_meta($_POST['user_id'], "company_id", $_POST['company_id']);

            wp_redirect( add_query_arg([
                "success" => "1"
            ], get_permalink($post->ID)) );
            exit;
        }
    }

    function checkUserLogin($username, $password) {
        $user = wp_authenticate_username_password(null, $username, $password);
        
        if ( is_wp_error( $user ) )  {
            $error_string = $user->get_error_message();
            return json_encode([
                'success' => false,
                'message' => $error_string
            ]);
        } else { // success:
            return json_encode([
                'success' => true,
                'user' => $user
            ]);
        }

    }

    function my_page_template_redirect()
    {
        if ( is_page( 'questions' ) ) {
            wp_redirect( home_url( '/signup/' ) );
            exit();
        }
    }

    function getAnswersOfCompany( $intCompanyID = '' )
    {
        if (empty($intCompanyID)) $intCompanyID = 42;

        $arrAnswers = get_posts([
            'post_type'     => 'answer',
            'post_status'   => 'publish',
            'numberposts'   => -1,
            'meta_query' => array(
                array(
                    'key'   => 'company_id',
                    'value' => $intCompanyID,
                )
            )
        ]);

        $arrResponse = [];
        $totalNotResponded = 0;
        $totalYes = 0;
        $totalNo = 0;
        $totalNotSure = 0;
        $totalInProgress = 0;

        if (count($arrAnswers)) {

        foreach ($arrAnswers as $objAnswer)
        {
            $answers = get_field("answers", $objAnswer->ID);
            $arrUserAnswers = explode("\n", $answers);
            $userAnswersCount = 0;
            $userNotResponded = 0;
            $userYes = 0;
            $userNo = 0;
            $userNotSure = 0;
            $userInProgress = 0;
            foreach ($arrUserAnswers as $strUserAnswer)
            {
                if (!empty($strUserAnswer))
                {
                    $userAnswersCount++;

                    $tempArray = explode("=", $strUserAnswer);

                    if ($tempArray[0] === "NOT RESPONDED") $userNotResponded++;
                    if ($tempArray[0] === "YES") $userYes++;
                    if ($tempArray[0] === "NO") $userNo++;
                    if ($tempArray[0] === "NOT SURE") $userNotSure++;
                    if ($tempArray[0] === "IN PROGRESS") $userInProgress++;

                }
            }

            $totalNotResponded += ($userNotResponded/$userAnswersCount) * 100;
            $totalYes += ($userYes/$userAnswersCount) * 100;
            $totalNo += ($userNo/$userAnswersCount) * 100;
            $totalNotSure += ($userNotSure/$userAnswersCount) * 100;
            $totalInProgress += ($userInProgress/$userAnswersCount) * 100;

        }

        $arrResponse = [
            'NotResponded' => ceil($totalNotResponded/count($arrAnswers)),
            'Yes' => ceil($totalYes/count($arrAnswers)),
            'No' => ceil($totalNo/count($arrAnswers)),
            'NotSure' => ceil($totalNotSure/count($arrAnswers)),
            'InProgress' => ceil($totalInProgress/count($arrAnswers))
        ];

        } else {

            $arrResponse = [
                'NotResponded' => 0,
                'Yes' => 0,
                'No' => 0,
                'NotSure' => 0,
                'InProgress' => 0
            ];

        }

        

        

        return $arrResponse;
    }

    function search_companies($search_term)
    {
        return get_posts([
            'post_type' => 'company',
            'post_status' => 'publish',
            's' => $search_term
        ]);        
    }
    
}

global $clsCompliance;
$clsCompliance = new clsCompliance();
