const express = require("express");
const bodyParser = require("body-parser");
const nodemailer = require("nodemailer");
const cors = require("cors"); // Подключаем CORS

const app = express();
const PORT = 3000;

app.use(cors());


app.use(bodyParser.json());

const transporter = nodemailer.createTransport({
    service: "gmail",
    auth: {
        user: "ihornovozhenin@gmail.com",
        pass: "lzsm qsaf fowi xiaq",
    },
});

// Route to handle sending emails
app.post("/send-email", (req, res) => {
    const { name, email, phone, quantity, price } = req.body;

    const mailOptions = {
        from: "ihornovozhenin@gmail.com",
        to: email,
        subject: "Your Details from Wizard",
        text: `
        Hello, ${name}!

        Thank you for using our wizard. Here are the details you provided:

        - Name: ${name}
        - Email: ${email}
        - Phone: ${phone}
        - Quantity: ${quantity}
        - Price: $${price}

        If you have any questions, feel free to contact us.

        Best regards,
        The Wizard Team
        `,
    };

    // Send email
    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            console.error("Error while sending email:", error.message);
            console.error("Full error details:", error);
            return res.status(500).send(`Failed to send email. Error: ${error.message}`);
        }
        console.log("Email successfully sent:", info.response);
        res.status(200).send("Email sent successfully!");
    });
});

app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
