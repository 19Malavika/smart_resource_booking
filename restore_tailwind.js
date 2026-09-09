const fs = require('fs');

const transcriptPath = '/home/lilac-pc-12/.gemini/antigravity-ide/brain/39e4a465-6f50-4745-b762-abf1a1959b3f/.system_generated/logs/transcript_full.jsonl';
const lines = fs.readFileSync(transcriptPath, 'utf8').split('\n');

for (const line of lines) {
    if (!line.trim()) continue;
    const step = JSON.parse(line);
    
    // We want steps 387 and above (the Tailwind rewrite)
    if (step.step_index >= 387 && step.tool_calls) {
        for (const call of step.tool_calls) {
            if (call.name === 'write_to_file' && call.args && call.args.TargetFile && call.args.CodeContent) {
                // Strip quotes from TargetFile
                let target = call.args.TargetFile.replace(/^"|"$/g, '');
                let content = call.args.CodeContent;
                
                // If it's a JSON string, parse it. 
                // The tool args are passed as JSON strings in the transcript for some reason?
                // Actually, if it's already an object in transcript, it might be a string that needs parsing or it might be raw.
                // Let's handle both.
                if (typeof content === 'string') {
                    try {
                        content = JSON.parse(content);
                    } catch(e) {
                        // might not be JSON encoded string
                        try {
                            content = JSON.parse(`"${content}"`);
                        } catch(e) {}
                    }
                }
                
                fs.writeFileSync(target, content);
                console.log(`Restored ${target} from step ${step.step_index}`);
            }
        }
    }
}
