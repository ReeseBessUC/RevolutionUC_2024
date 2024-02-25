
<br>

<h3>Thoughts for the educated compliance professional.</h3>

<?php
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');

$arrMemories = get_posts([
    'post_type'     => 'memory',
    'post_status'   => 'publish',
    'numberposts'   => -1,
    
]);


    //$text = $_POST['ai_text'];


    $apiKey = get_option("openai_key");

        // Set the prompt for text generation
        //$prompt = "knock knock joke about data compliance";
        $prompt = "provide me a joke about data compliance, but make sure it is work place appropriate, and not a duplicate from a previous output.";
        

        // Set the parameters for the API request
        $data = array(
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ],
            //"message" => $prompt,
            "max_tokens" => 100, // Maximum number of tokens to generate
            "temperature" => 0.7, // Controls the randomness of the generated text
            "top_p" => 1, // Controls diversity by sampling from the most likely tokens
            "n" => 1 // Number of completions to generate
        );

        // Set the headers for the API request
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        );

        // Make the API request
        $ch = curl_init("https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        // Decode and print the response
        $result = json_decode($response, true);
        
        if (isset($result['choices'][0]['message']['content']))
        {
            ?>
            <br>
            <div class="alert alert-success">
                <?php echo $result['choices'][0]['message']['content']; ?>
            </div>
            <?php

            wp_insert_post([
                'post_type' => 'memory',
                'post_title' => 'Memory ' . date("d M, Y h:i"),
                'post_content' => $result['choices'][0]['message']['content'],
                'post_status' => 'publish'
            ]);
        }
        else
        {
            ?>
            <div class="alert alert-danger">
                <?php
                
                p_r($result)
                
                ?>
            </div>
            <?php
        }



?>
<style>
    table {
        border: 1px !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Memory</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                

                foreach ($arrMemories as $intIndex => $objMemory)
                {
                    
                    ?>
                    <tr>
                        <td><?php echo ($intIndex + 1); ?></td>
                        <td><?php echo $objMemory->post_content; ?></td>
                        
                        
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>





