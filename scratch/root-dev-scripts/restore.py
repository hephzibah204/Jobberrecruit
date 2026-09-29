import json

p = 'C:/Users/hephz/.gemini/antigravity-ide/brain/b56129c2-6413-498b-b70d-74b5e269b2d8/.system_generated/logs/transcript.jsonl'
f = {}
with open(p, 'r', encoding='utf-8') as file:
    for l in file:
        if 'tool_calls' in l:
            try:
                data = json.loads(l)
                for tc in data.get('tool_calls', []):
                    if tc.get('name') == 'write_to_file':
                        args = tc.get('args', {})
                        target = args.get('TargetFile', '').strip('"')
                        if 'demo' in target and 'home' in target:
                            content = args.get('CodeContent', '')
                            if content.startswith('"') and content.endswith('"'):
                                content = content[1:-1].encode('utf-8').decode('unicode_escape')
                            f[target.replace('\\\\', '\\')] = content
            except Exception as e:
                pass

for t, c in f.items():
    if c:
        with open(t, 'w', encoding='utf-8') as out:
            out.write(c)
print(f"Restored {len(f)} files")
