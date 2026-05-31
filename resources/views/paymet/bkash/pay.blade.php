<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>bKash Payment</title>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        button, a {
            background-color: #007bff;
            color: #fff;
            padding: 15px 32px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        #labelText {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
        }

        .copyBtn {
            background-color: #ff5733;
        }
    </style>
</head>
<body>
<div class="center" style="flex-direction: column;">
    @if(isset($status) && $status == "success")
        <form action="javascript:void(0)">
            @if(isset($createPayment))
                <label for="amount" id="labelText">
                    <span>Create Payment Response</span>
                    <button class="copyBtn" id="createResponseBtn" onclick="copyCreatePayment()">Copy</button>
                </label>
                <textarea id="" cols="50" rows="10">{{ $createPayment ?? "" }}</textarea>
            @endif
            @if(isset($executePayment))
                <label for="amount" id="labelText" style="margin-top: 50px">
                    <span>Execute Payment Response</span>
                    <button class="copyBtn" id="executeResponseBtn" onclick="copyExecutePayment()">Copy</button>
                </label>
                <textarea id="" cols="50" rows="10">{{ $executePayment ?? "" }}</textarea>
            @endif
            <a href="{{ route('bkash.sandbox.pay') }}" class="copyBtn" style="margin-top: 30px;">Try Again</a>
        </form>

    @else
        @if(isset($status) && $status == "error")
            <p style="color: red;">Process error try again</p>
        @endif
        <form action="{{ route('bkash.sandbox.verification') }}" method="POST">
            @csrf
            <label for="amount">Enter Your Sandbox Credentials</label>
            <input type="text" id="app_key" name="app_key" value="" placeholder="App key">
            <input type="text" id="app_secret" name="app_secret"
                   value=""
                   placeholder="App secret">
            <input type="text" id="username" name="username" value="" placeholder="Username">
            <input type="text" id="password" name="password" value="" placeholder="Password">

            <input type="text" id="amount" name="amount" value="510" placeholder="Amount" required>
            <button type="submit" id="bKash_button" onclick="handleSubmit(event)">Pay with bKash</button>
        </form>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script !src="">

    // Function to decode HTML entities and parse the JSON
    function decodeAndParseJson(encodedString) {
        // Decode HTML entities by creating an invisible DOM element and setting its innerHTML
        const parser = new DOMParser();
        const decodedString = parser.parseFromString(`<!doctype html><body>${encodedString}`, 'text/html').body.textContent;

        // Parse the decoded string into a JavaScript object
        try {
            return JSON.parse(decodedString);
        } catch (e) {
            console.error("Failed to parse JSON:", e);
            return null;
        }
    }

    // Copy Create Payment Response
    const copyCreatePayment = () => {

        const paymentData = decodeAndParseJson('{{ $createPayment ?? "" }}');
        const paymentDataString = JSON.stringify(paymentData, null, 4);

        // Use the modern Clipboard API
        navigator.clipboard.writeText(paymentDataString).then(function () {
            toastr.success("Create Payment Response successfully copied to clipboard!");
            $("#createResponseBtn").html("Successfully copied").css("background-color", "green");

            const btnText = 'Copy';

            setTimeout(function () {
                $("#createResponseBtn").html(btnText).css("background-color", "#ff5733");
            }, 2500);

        }).catch(function (error) {
            toastr.error("Failed to copy: " + error);
        });
    }

    // Copy Execute Payment Response
    const copyExecutePayment = () => {

        const executePaymentData = decodeAndParseJson('{{ $executePayment ?? "" }}');
        const executePaymentDataString = JSON.stringify(executePaymentData, null, 4);

        // Use the modern Clipboard API
        navigator.clipboard.writeText(executePaymentDataString).then(function () {
            toastr.success("Execute Payment Response successfully copied to clipboard!");
            $("#executeResponseBtn").html("Successfully copied").css("background-color", "green");

            const btnText = 'Copy';

            setTimeout(function () {
                $("#executeResponseBtn").html(btnText).css("background-color", "#ff5733");
            }, 2500);

        }).catch(function (error) {
            toastr.error("Failed to copy: " + error);
        });
    }

</script>
</body>
</html>
