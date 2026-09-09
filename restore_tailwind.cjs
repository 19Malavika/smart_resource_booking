const fs = require('fs');
const transcriptPath = '/home/lilac-pc-12/.gemini/antigravity-ide/brain/39e4a465-6f50-4745-b762-abf1a1959b3f/.system_generated/logs/transcript_full.jsonl';
const lines = fs.readFileSync(transcriptPath, 'utf8').split('\n');

for (const line of lines) {
    if (!line.trim()) continue;
    try {
        const step = JSON.parse(line);
        // We want steps 380 and above (the Tailwind rewrite)
        if (step.step_index >= 380 && step.tool_calls) {
            for (const call of step.tool_calls) {
                if (call.name === 'write_to_file' && call.args && call.args.TargetFile && call.args.CodeContent) {
                    let target = call.args.TargetFile;
                    let content = call.args.CodeContent;
                    
                    if (target.includes('/resources/views/') || target.includes('tailwind.config.js') || target.includes('Login.vue')) {
                        fs.writeFileSync(target, content);
                        console.log(`Restored ${target} from step ${step.step_index}`);
                    }
                }
            }
        }
    } catch(e) {}
}
