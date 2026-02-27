<!-- We use Split.js for IDE draggable panes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/split.js/1.6.5/split.min.js"></script>

<!-- Monaco Editor Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs/loader.min.js"></script>

<div class="flex-1 w-full flex flex-col h-[calc(100vh-73px)] h-full overflow-hidden">
    <!-- Top Bar Toolbar -->
    <div class="w-full bg-cardDark border-b border-borderCode px-4 py-2 flex justify-between items-center text-sm shadow-md z-10 flex-shrink-0">
        <div class="flex items-center gap-3">
            <a href="<?= URLROOT ?>/student/dashboard" class="text-textSecondary hover:text-white transition-colors flex items-center gap-1 group bg-[#21262d] px-3 py-1 rounded-md border border-borderCode hover:border-textSecondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                عودة
            </a>
            <span class="text-gray-500 font-bold">/</span>
            <span class="text-brandPurple font-semibold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <?= $exercise['title'] ?>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <select id="themeSelector" class="bg-bgDark border border-borderCode text-textPrimary rounded-md px-2 py-1 outline-none focus:border-brandPurple cursor-pointer h-8">
                <option value="vs-dark">Dark Theme</option>
                <option value="hc-black">High Contrast</option>
            </select>
            <div class="h-5 w-px bg-borderCode mx-2"></div>
            <button id="runBtn" class="bg-[#21262d] hover:bg-[#30363d] text-white px-4 h-8 rounded-md font-semibold transition-colors flex items-center gap-2 border border-[#30363d]" onclick="runCodeLocal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brandYellow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Run Code
            </button>
            <button class="bg-brandGreen hover:bg-[#2ea043] text-white px-4 h-8 rounded-md font-semibold transition-colors shadow-lg shadow-brandGreen/20 flex items-center gap-2" id="submitBtn" onclick="submitToAI()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Submit
            </button>
        </div>
    </div>

    <!-- Main Split Area -->
    <div class="flex-1 flex flex-row overflow-hidden w-full" id="ide-container">

        <!-- Left Panel: Problem Statement -->
        <div class="bg-bgDark overflow-y-auto" id="left-panel">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-6 text-xl">
                    <span class="bg-brandYellow/10 text-brandYellow p-1 rounded">📝</span>
                    <h2 class="font-bold text-white m-0">المشكلة (Problem)</h2>
                </div>

                <div class="prose prose-invert max-w-none text-textPrimary leading-relaxed mb-8">
                    <?= nl2br($exercise['description']) ?>
                </div>

                <h3 class="font-bold text-white mb-3 flex items-center gap-2 text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    مثال (Example)
                </h3>
                <div class="bg-[#010409] border border-borderCode rounded-lg overflow-hidden mb-6">
                    <div class="grid grid-cols-2 text-sm">
                        <div class="p-3 border-l border-[#30363d]">
                            <div class="text-textSecondary font-bold mb-2">Input</div>
                            <pre class="font-mono text-gray-300 m-0 leading-tight">10 20 30 40 50</pre>
                        </div>
                        <div class="p-3">
                            <div class="text-textSecondary font-bold mb-2">Expected Output</div>
                            <pre class="font-mono text-brandGreen m-0 leading-tight">150</pre>
                        </div>
                    </div>
                </div>

                <!-- AI Feedback Container -->
                <div id="aiFeedbackBox" class="hidden relative mt-8 pt-6 border-t border-borderCode">
                    <div class="absolute -top-3 right-4 bg-bgDark px-2">
                        <span class="flex items-center gap-1 text-sm font-bold text-brandPurple bg-brandPurple/10 px-2 py-0.5 rounded border border-brandPurple/20">
                            🌟 المحلل الذكي الـ AI
                        </span>
                    </div>
                    <div class="bg-[#161b22] border border-brandPurple/30 rounded-lg p-5 shadow-lg shadow-brandPurple/5">
                        <p id="aiGeneralText" class="text-white m-0 leading-relaxed"></p>

                        <div class="mt-4 bg-brandYellow/10 border-r-4 border-brandYellow p-3 rounded text-sm text-gray-300">
                            <strong class="text-brandYellow mb-1 block">💡 تلميح:</strong>
                            <span id="aiHintText"></span>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-gray-400 text-sm">الدرجة المحتسبة:</span>
                            <span class="text-2xl font-black" id="aiScoreLabel"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Panel: Editor & I/O -->
        <div class="flex flex-col bg-cardDark border-r border-[#30363d]" id="right-panel">

            <!-- Editor Header -->
            <div class="bg-[#161b22] border-b border-borderCode px-4 py-1.5 flex items-center justify-between text-xs font-mono text-gray-400 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    main.cpp
                </div>
                <span>C++17 (GCC 9)</span>
            </div>

            <!-- Monaco Editor Container -->
            <div id="monacoEditorContainer" class="flex-1 w-full bg-[#1e1e1e] border-none" dir="ltr"></div>

            <!-- Real TextArea to store data for submission -->
            <textarea id="codeEditor" class="hidden"><?= $exercise['starter_code'] ?></textarea>

            <!-- Bottom I/O Panel -->
            <div class="border-t border-borderCode bg-bgDark flex-shrink-0 h-[25vh] flex flex-col">
                <div class="flex text-xs font-bold text-gray-400 bg-[#161b22] border-b border-borderCode">
                    <button id="tabCustomInput" class="px-4 py-2 border-b-2 border-brandPurple text-brandPurple bg-bgDark h-full flex items-center gap-1" onclick="switchIoTab('input')">Custom Input</button>
                    <button id="tabOutput" class="px-4 py-2 hover:bg-bgDark transition-colors h-full flex items-center gap-1" onclick="switchIoTab('output')">Output</button>
                </div>
                <div class="flex-1 bg-[#0d1117] relative">
                    <textarea id="customInput" class="absolute inset-0 w-full h-full bg-transparent border-none text-gray-300 font-mono text-sm p-3 outline-none resize-none placeholder-gray-600" dir="ltr" placeholder="Enter custom input here..."></textarea>
                    <textarea id="outputConsole" class="absolute inset-0 w-full h-full bg-transparent border-none text-gray-300 font-mono text-sm p-3 outline-none resize-none placeholder-gray-600 hidden" dir="ltr" readonly placeholder="Output will appear here..."></textarea>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Split.js Gutters styles */
    .gutter {
        background-color: #30363d;
        background-repeat: no-repeat;
        background-position: 50%;
        transition: background-color 0.15s ease;
        z-index: 5;
    }

    .gutter.gutter-horizontal {
        cursor: col-resize;
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAIklEQVQoU2M4c+bMfxAGAgYYQwkYTiDqQzFqAoPihKhmAAAV+S02O44E1wAAAABJRU5ErkJggg==');
    }

    .gutter:hover,
    .gutter.gutter-horizontal:hover {
        background-color: #8b5cf6;
    }

    /* Ensure the main container in header correctly expands */
    main {
        padding: 0 !important;
        max-width: none !important;
    }
</style>

<script>
    // Initialize Split.js for left/right columns
    Split(['#left-panel', '#right-panel'], {
        sizes: [35, 65],
        minSize: [300, 400],
        gutterSize: 6,
        cursor: 'col-resize'
    });

    // Initialize Monaco Editor
    require.config({
        paths: {
            'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs'
        }
    });
    require(['vs/editor/editor.main'], function() {
        var editorBox = document.getElementById('codeEditor');

        window.monacoEditor = monaco.editor.create(document.getElementById('monacoEditorContainer'), {
            value: editorBox.value,
            language: 'cpp',
            theme: 'vs-dark',
            automaticLayout: true,
            fontSize: 16,
            fontFamily: "'Courier New', Courier, monospace",
            minimap: {
                enabled: false
            },
            padding: {
                top: 16
            },
            scrollBeyondLastLine: false,
            roundedSelection: false,
        });

        // Sync theme selector
        document.getElementById('themeSelector').addEventListener('change', function(e) {
            monaco.editor.setTheme(e.target.value);
        });
    });

    // Tab Switcher Logic
    function switchIoTab(tab) {
        const tabInput = document.getElementById('tabCustomInput');
        const tabOut = document.getElementById('tabOutput');
        const customInput = document.getElementById('customInput');
        const outputConsole = document.getElementById('outputConsole');

        if (tab === 'input') {
            tabInput.classList.add('border-b-2', 'border-brandPurple', 'text-brandPurple', 'bg-bgDark');
            tabInput.classList.remove('hover:bg-bgDark');
            tabOut.classList.remove('border-b-2', 'border-brandPurple', 'text-brandPurple', 'bg-bgDark');
            tabOut.classList.add('hover:bg-bgDark');

            customInput.classList.remove('hidden');
            outputConsole.classList.add('hidden');
        } else {
            tabOut.classList.add('border-b-2', 'border-brandPurple', 'text-brandPurple', 'bg-bgDark');
            tabOut.classList.remove('hover:bg-bgDark');
            tabInput.classList.remove('border-b-2', 'border-brandPurple', 'text-brandPurple', 'bg-bgDark');
            tabInput.classList.add('hover:bg-bgDark');

            outputConsole.classList.remove('hidden');
            customInput.classList.add('hidden');
        }
    }

    // ======= Run C++ Code using Wandbox API =======
    async function runCodeLocal() {
        if (!window.monacoEditor) return;
        const code = window.monacoEditor.getValue();
        const customInput = document.getElementById('customInput').value;
        const runBtn = document.getElementById('runBtn');
        const originalBtnHTML = runBtn.innerHTML;
        const outputConsole = document.getElementById('outputConsole');

        // UI Change to compiling state
        runBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-brandYellow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Running...`;
        runBtn.disabled = true;
        switchIoTab('output');

        outputConsole.value = "Compiling and running on Wandbox Servers...\n";
        outputConsole.classList.remove('text-brandRed');
        outputConsole.classList.add('text-gray-300');

        try {
            const response = await fetch('https://wandbox.org/api/compile.json', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    compiler: 'gcc-head',
                    code: code,
                    stdin: customInput
                })
            });

            const result = await response.json();

            if (result.status === "0") {
                // Success
                outputConsole.classList.replace('text-brandRed', 'text-gray-300');
                let outText = "";
                if (result.compiler_message) outText += "Compiler warnings/messages:\n" + result.compiler_message + "\n\n";
                if (result.program_message) outText += result.program_message;
                if (!result.program_message && !result.compiler_message) outText = "Program finished with no output.";
                outputConsole.value = outText;
            } else {
                // Error (Compilation or runtime)
                outputConsole.classList.replace('text-gray-300', 'text-brandRed');
                let errText = "Error Executing Code:\n\n";
                if (result.compiler_error) errText += result.compiler_error;
                if (result.program_error) errText += "\n" + result.program_error;
                outputConsole.value = errText;
            }

        } catch (error) {
            outputConsole.classList.replace('text-gray-300', 'text-brandRed');
            outputConsole.value = "Network Error: Failed to reach compilation server.\n" + error.message;
        } finally {
            runBtn.innerHTML = originalBtnHTML;
            runBtn.disabled = false;
        }
    }

    function submitToAI() {
        if (!window.monacoEditor) return;
        const btn = document.getElementById('submitBtn');
        const code = window.monacoEditor.getValue();
        const fbBox = document.getElementById('aiFeedbackBox');

        // Push back to textarea
        document.getElementById('codeEditor').value = code;

        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Analyzing...`;
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        // Simulate AI Server Response
        setTimeout(() => {
            const aiResponse = {
                score: 80,
                general_feedback: "عمل رائع يا بطل! يبدو أن الحلقة التكرارية تدور مرة زائدة عن المطلوب.",
                hint: "راجع شرط إيقاف الحلقة (i <= 5). الدورة تبدأ من 1 وتنتهي عند 6 حالياً!"
            };

            document.getElementById('aiGeneralText').innerText = aiResponse.general_feedback;
            document.getElementById('aiHintText').innerText = aiResponse.hint;

            const scoreLabel = document.getElementById('aiScoreLabel');
            scoreLabel.innerText = aiResponse.score + "%";
            scoreLabel.className = 'text-2xl font-black ' + (aiResponse.score >= 70 ? 'text-brandGreen' : 'text-brandYellow');

            fbBox.classList.remove('hidden');

            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Submit Again`;
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }, 1500);
    }
</script>