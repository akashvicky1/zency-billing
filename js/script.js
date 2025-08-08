document.getElementById("reportForm").addEventListener("submit", function(e){
    e.preventDefault();

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let message = document.getElementById("message").value;
    let msgBox = document.getElementById("msg");

    msgBox.textContent = "Sending...";

    Email.send({
        Host: "smtp.gmail.com",
        Username: "zencyinfo@gmail.com",
        Password: "vczb faju eqwy ihaf", // Gmail App Password
        To: "zencyinfo@gmail.com",
        From: "zencyinfo@gmail.com",
        Subject: `Sales Report from ${name}`,
        Body: `
            <h3>Sales Report Submission</h3>
            <p><strong>Name:</strong> ${name}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p><strong>Report:</strong> ${message}</p>
        `
    }).then(
        () => msgBox.textContent = "✅ Report sent successfully!",
        () => msgBox.textContent = "❌ Failed to send. Please try again."
    );
});
