# Customer SMS

In Admin > Customers, choose **Send SMS**, enter a message and submit. The server uses the selected customer's stored mobile number, normalizes it to Bangladesh international format, and sends through the same SMS API Setting used for order confirmations. The modal stays open to display the result; Cancel closes it.

Before sending, configure and enable Admin > Settings > SMS API Setting using your actual gateway documentation. Set endpoint, sender ID, API key/secret, request method, JSON/form encoding, phone format and parameter/header mappings. Supported placeholders: `{phone}`, `{message}`, `{api_key}`, `{api_secret}`, `{sender_id}`. If your provider returns errors inside HTTP 200 responses, configure Success Path and Success Value so rejected messages are not marked submitted.

Manual customer messages are sent immediately and do not require a queue worker. Provider acceptance is displayed as **submitted**, not proof of delivery. Messages and results appear under Customer Profile > Recent SMS Messages. A gateway timeout does not automatically retry because the provider might already have accepted the message. Check the provider's records before reopening the modal and sending again.

Requests require admin authorization, CSRF validation and a unique submission token, with a rate limit of 10 requests per minute. Submitting the same token twice does not issue a second provider request. API credentials and raw response bodies are not displayed or logged by the sending service.

No provider credentials were changed, no SMS was sent, and no tests were run while implementing this feature.
