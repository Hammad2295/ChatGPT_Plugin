<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <style>
        .wrap {
            background: #fff;
            padding: 5rem;
            border-radius: 10px;
            text-align: center;
        }

        .form-field textarea {
            font-size: 20px;
            border-radius: 10px;
            padding: 0.5rem;
        }

        .drop-container {
            position: relative;
            display: flex;
            gap: 10px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 200px;
            padding: 20px;
            border-radius: 10px;
            border: 2px dashed #555;
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
        }

        .drop-container:hover {
            background: #eee;
            border-color: #111;
        }

        .drop-container:hover .drop-title {
            color: #222;
        }

        .drop-title {
            color: #444;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            transition: color .2s ease-in-out;
        }

        .pdf-input {
            border: 1px solid #111;
            padding: 10px;
            border-radius: 10px;
        }

        input[type=file]::file-selector-button {
            margin-right: 20px;
            border: none;
            background: #084cdf;
            padding: 10px 20px;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            transition: background .2s ease-in-out;
        }

        input[type=file]::file-selector-button:hover {
            background: #0d45a5;
        }

        .button {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 18px;
            background: #eee;
            cursor: pointer;
        }

        .button:hover {
            color: #fff;
            background: #999
        }

        .notice {
            text-align: center;
        }

        .notice p {
            padding: 20px;
            border-radius: 10px;
            border: 2px dashed #555;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .center div {
            margin-bottom: 30px;
        }

        .center div label {
            font-size: 20px;
            margin-right: 10px;
        }

        .center div input {
            font-size: 20px;
            padding: 5px;
            border-radius: 10px;
        }
    </style>
</head>

<body>


    <?php
    function gpt4_settings_page()
    {
        ?>
        <div class="wrap">
            <form method="post" action="chatgpt-pdf-plugin.php" enctype="multipart/form-data">
                <div class="form-field">
                    <textarea id="prompt" name="prompt" rows="4" cols="50" required
                        placeholder="Enter Prompt Here"></textarea>
                </div>
                <br>

                <div class="form-field">
                    <label for="images" class="drop-container" id="dropcontainer">
                        <span class="drop-title">Drop files here</span>
                        or
                        <input class="pdf-input" type="file" name="pdf" id="pdf" accept=".pdf">
                    </label>
                </div>
                <br>

                <input type="submit" name="submit" class="button button-primary" value="Generate Response">
            </form>


        </div>
        <?php
    }

    gpt4_settings_page();
    ?>

    <?php
    session_start();

    // Check if the chatgpt_response session variable exists
    if (isset($_SESSION['chatgpt_response'])) {
        $response = $_SESSION['chatgpt_response'];

        // Display the response
        echo "<div class='notice notice-success wrap'>
                <h2>Response</h2>
                <p id='responseMessage'>$response</p>
            </div>
            <div class='center'>
                    <div class='form-field'>
                        <label for='email_id'>Email for Response:</label>
                        <input type='email' id='email_id' required>
                    </div>
                    <button type='submit' class='button button-primary'onclick='SendMail()'>Get Response</button>
            </div>";

        // Clear the session variable to prevent displaying the same response again
        unset($_SESSION['chatgpt_response']);
    }
    ?>

</body>

<script>
    function SendMail() {
        // Retrieve the response message from the <p> element
        var responseElement = document.getElementById('responseMessage');
        var msg = responseElement.textContent; // Get the text content of the <p> element

        alert(msg);
        var params = {
            email_id: document.getElementById('email_id').value,
            message: msg
        }

        emailjs.send("service_t634ybd", "template_nkihpjo", params).then(function (res) {
            alert("Succes" + res.status);
        });
    }
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js">
</script>
<script type="text/javascript">
    (function () {
        emailjs.init("AuwT7IovdTv6bGdIJ");
    })();
</script>

</html>