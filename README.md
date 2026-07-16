# Playwright Portfolio

![Playwright Tests](https://github.com/KarolinaPanada/playwright-portfolio/actions/workflows/playwright.yml/badge.svg)

## О проекте

Портфолио-проект по автоматизации тестирования: UI и API тесты на Playwright + TypeScript с использованием Page Object Model, кастомных fixtures и CI на GitHub Actions.

## Стек

- TypeScript
- Playwright (`@playwright/test`)
- GitHub Actions (CI)

## Как запустить

```bash
npm install
npx playwright install --with-deps
cp .env.example .env   # заполнить переменные окружения
npx playwright test
```

Полезные команды:

```bash
npm run test:ui       # только UI-тесты
npm run test:api      # только API-тесты
npm run test:headed   # запуск с открытым браузером
npm run test:debug    # запуск в режиме отладки
npm run report        # открыть последний HTML-отчёт
```

## Структура проекта

```
src/
  pages/      — Page Object Model (классы страниц)
  fixtures/   — кастомные Playwright fixtures
  data/       — тестовые данные
  utils/      — вспомогательные модули (например, API-клиент)
tests/
  ui/         — UI-тесты
  api/        — API-тесты
```

## Отчёты

После прогона тестов HTML-отчёт открывается командой:

```bash
npx playwright show-report
```

В CI отчёт сохраняется как артефакт workflow-запуска (доступен на вкладке Actions в GitHub).

## CI

Тесты автоматически запускаются в GitHub Actions при push и pull request в ветку `main` (см. [.github/workflows/playwright.yml](.github/workflows/playwright.yml)).
