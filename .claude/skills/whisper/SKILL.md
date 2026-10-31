---
name: whisper
description: Транскрибация аудио/видео локальным Whisper (whisper.cpp через `npx hyperframes transcribe`) в слова с таймкодами, SRT/VTT — для субтитров, караоке-титров и сценариев. Используй, когда просят расшифровать/транскрибировать видео или голосовое, сделать субтитры или получить тайминги слов.
---

# Whisper: транскрибация

Работает локально, без API-ключей. Нужны Node.js и ffmpeg (см. скил `ffmpeg-skill`).

```bash
npx hyperframes transcribe audio.mp3 --model small.en              # английский
npx hyperframes transcribe video.mp4 --model small --language ru   # русский
npx hyperframes transcribe audio.mp3 --model small                 # язык неизвестен (автоопределение)
npx hyperframes transcribe subtitles.srt                           # импорт готовых субтитров (.srt/.vtt/.json)
```

## Правило языка (обязательное)

Модели с суффиксом `.en` **переводят** не-английскую речь в английский и портят оригинал. Для русского и любого не-английского всегда `--model small` (или `medium`/`large-v3`) + `--language <iso>`, например `ru`. Всегда указывай `--model` явно: по умолчанию стоит `small.en`.

| Модель | Размер | Когда |
| --- | --- | --- |
| `tiny` | 75 MB | быстрый черновик |
| `base` | 142 MB | короткие чистые записи |
| `small` | 466 MB | по умолчанию для многоязычного контента |
| `medium` | 1.5 GB | шум, музыка с вокалом |
| `large-v3` | 3.1 GB | максимальное качество |

## Формат результата

Плоский массив слов: `[{ "id": "w0", "text": "Привет", "start": 0.0, "end": 0.5 }, ...]`.

Дальше: субтитры и титры — скилы `hyperframes` / `remotion-motion-graphics`; прожиг субтитров в файл — `ffmpeg-skill` (`caption.py`). Детали проверки качества и импорт ответов OpenAI/Groq Whisper API — `.claude/skills/media-use/audio/references/transcribe.md` и `captions/transcript-handling.md` в нём.
