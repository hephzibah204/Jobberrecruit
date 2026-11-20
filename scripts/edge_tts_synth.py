import sys
import asyncio
import os

try:
    import edge_tts
except ImportError:
    sys.stderr.write("edge_tts module is not installed\n")
    sys.exit(2)

async def synthesize(text_file: str, voice: str, out_file: str, rate: str = "+0%", pitch: str = "+0Hz"):
    if not os.path.exists(text_file):
        sys.stderr.write(f"Text file not found: {text_file}\n")
        sys.exit(3)

    with open(text_file, 'r', encoding='utf-8') as f:
        text = f.read().strip()

    if not text:
        sys.stderr.write("Empty text provided\n")
        sys.exit(4)

    os.makedirs(os.path.dirname(os.path.abspath(out_file)), exist_ok=True)
    communicate = edge_tts.Communicate(text, voice, rate=rate, pitch=pitch)
    await communicate.save(out_file)

def main():
    if len(sys.argv) < 4:
        sys.stderr.write("Usage: python edge_tts_synth.py <text_file> <voice> <output_file> [rate] [pitch]\n")
        sys.exit(1)

    text_file = sys.argv[1]
    voice = sys.argv[2]
    out_file = sys.argv[3]
    rate = sys.argv[4] if len(sys.argv) > 4 else "+0%"
    pitch = sys.argv[5] if len(sys.argv) > 5 else "+0Hz"

    asyncio.run(synthesize(text_file, voice, out_file, rate, pitch))

if __name__ == '__main__':
    main()
