<?php
/*
Plugin Name: ChatGPT PDF Interaction
Description: Allow users to input a prompt and upload a PDF to interact with ChatGPT.
Version: 1.0
Author: Hax
*/

include 'vendor/autoload.php';
// Initialize and load PDF Parser library 
$parser = new \Smalot\PdfParser\Parser();

// Shortcode to display the ChatGPT form
function chatgpt_form_shortcode()
{
    ob_start();
    include(plugin_dir_path(__FILE__) . 'form.php');
    return ob_get_clean();
}
add_shortcode('chatgpt_form', 'chatgpt_form_shortcode');


if (isset($_POST['submit'])) {
    $prompt = $_POST['prompt'];

    if ($_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $pdf_file = $_FILES['pdf']['tmp_name'];

        $pdf = $parser->parseFile($pdf_file);
        // Extract text from PDF 
        $pdf_content = $pdf->getText();


        $new_prompt = $prompt . "   " . $pdf_content;


        // Set up your OpenAI API key
        $api_key = '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'; // Replace with your actual API key

        // Make an API request to GPT-4
        $response = send_request_to_gpt4($new_prompt, $api_key);


        session_start();
        $_SESSION['chatgpt_response'] = $response;

        // Redirect back to form.php
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;

    } else {
        $new_prompt = $prompt;


        // Set up your OpenAI API key
        $api_key = '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'; // Replace with your actual API key

        // Make an API request to GPT-4
        $response = send_request_to_gpt4($new_prompt, $api_key);


        session_start();
        $_SESSION['chatgpt_response'] = $response;

        // Redirect back to form.php
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
}

// Function to make an API request to GPT-4
function send_request_to_gpt4($prompt, $api_key)
{

    // GPT-4 API endpoint (replace with the actual endpoint for GPT-4)
    $api_url = 'https://api.openai.com/v1/chat/completions';

    // Prepare the request data
    $data = [
        'model' => 'gpt-3.5-turbo',
        // Specify the GPT-4 model here
        'messages' => [
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.7,
    ];

    // Prepare cURL request
    $ch = curl_init($api_url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        )
    );

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        return 'cURL Error: ' . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $response_data = json_decode($response, true);


    // Check if 'choices' key exists in the response
    if (isset($response_data['choices']) && is_array($response_data['choices']) && count($response_data['choices']) > 0) {
        // Extract and return the generated text
        $response_content = $response_data['choices'][0]['message']['content'];

        return $response_content;
    } else {
        // Handle the case where 'choices' key is not present or empty
        return 'Error: Invalid API response';
    }

}

?>