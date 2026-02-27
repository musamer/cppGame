// public/js/main.js

function runCodeLocal() {
  alert("جارٍ تجميع الكود في بيئة معزولة (Sandbox)...");
}

function submitToAI() {
  const btn = document.getElementById("submitBtn");
  const code = document.getElementById("codeEditor").value;
  const fbBox = document.getElementById("aiFeedbackBox");

  btn.innerText = "جاري التحليل... ⏳";
  btn.disabled = true;

  // Simulate an AJAX call to the AIEngine logic
  setTimeout(() => {
    // Simulated Response from our AIEngine
    const aiResponse = {
      score: 80,
      general_feedback:
        "عمل رائع يا بطل! يبدو أن الحلقة التكرارية تدور مرة زائدة عن المطلوب.",
      hint: "راجع شرط إيقاف الحلقة (i <= 5).",
    };

    document.getElementById("aiGeneralText").innerText =
      aiResponse.general_feedback;
    document.getElementById("aiHintText").innerText = aiResponse.hint;
    document.getElementById("aiScore").innerText = aiResponse.score;

    fbBox.style.display = "block";

    btn.innerText = "تسليم للذكاء الاصطناعي 🧠";
    btn.disabled = false;

    if (aiResponse.score >= 70) {
      // Trigger confetti or XP gain animation
      alert("مبروك! لقد كسبت 40 XP 🎉");
    }
  }, 1500);
}
