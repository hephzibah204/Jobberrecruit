# AI tools audit — 2 November 2026

Scope: candidate mock interviews (questions, replies, voice, transcription, evaluation), salary negotiation, career advice, resume generation/improvement/tailoring/import/save/autosave/export, shared AI service and access filters. Additional AI routes inventoried: chatbot, employer assessment generation, admin assessment/blog/image/CV review tools. Those additional authoring tools require a separate detailed workflow pass; they are not certified by this report.

Method: source and route inspection, plus isolated execution and browser regression checks. No external AI calls, paid requests, production writes, or production sessions. Findings below describe the original defects.

## Fix status — 3 November 2026

Implemented fixes for findings 1–14: authenticated candidate access, CSRF and request quotas; sanitized review output; bounded audio/CV uploads; real interview completion with checked persistence and retry; candidate prompt context; feature-gate coverage; actual negotiation conversation history; save feedback and retry; bounded practice-score validation; nullable tailoring scores; and rollback on failed resume child writes.

Completion keys require migration `2026-11-02-200000_AddAiCompletionKeys.php` on deployment. Existing browser completion keys prevent duplicate retry inserts; these are not server-issued interview identities, and practice scores are not independently verified performance assessments.

Follow-up fixes: salary report now opens a printable report including conversation (Save as PDF through the print dialog), with blocked-popup feedback; generated advice is passed to the next page load instead of discarded, with fresh generation requests bypassing ordinary advice cache; global text-model overrides now work while task overrides take priority and audio keeps its own model; resume readiness explicitly describes profile completeness rather than claiming CV-content analysis; unsupported salary benchmark claims are removed; interview speech recognition and question context respect the selected English variant.

Validation: `tests/ai-tools-regression.php` exercises real access/CSRF/quota checks, sanitizer, transcript/score validation, candidate prompt context, model precedence, SQLite completion uniqueness and real resume-save rollback. `tests/ai-tools-client.cjs` exercises real browser save failures/retries, confirmed XP, stable completion keys, CSRF injection, actual transcripts and salary printing/popup failure. Live provider responses, production migration and production browser sessions remain unverified.

## High priority

1. **Interview APIs can be called anonymously.** `app/Config/Routes.php:655–658` registers four API routes outside the authenticated candidate group. The four Interview controllers extend the framework Controller and contain no user check. FeatureGateFilter only applies subscription checks when a user exists, so anonymous callers bypass the paid-mode check. Impact: unauthorized question/reply/audio requests and provider spend. Fix: authenticated candidate API group, enforce feature/subscription rules for every endpoint, return JSON 401/403, add quotas. Evidence: source-confirmed; no anonymous production requests sent.

2. **State-changing resume and career-tool routes are excluded from CSRF protection.** `app/Config/Filters.php:87–95` exempts AI endpoints, autosave, restore-autosave and career tools. Authenticated group membership is not CSRF verification. Resume autosave/restore can mutate stored content. Fix: restore CSRF checks, send the current token from each client, provide token-refresh handling. Evidence: source-confirmed; cross-site exploit not executed.

3. **Resume writing-review HTML is inserted without sanitization.** ResumeController::generateWritingReview returns the raw string from AiService::generate; the latter returns provider text unchanged. `app/Views/candidate/resume/builder.php:1763` inserts it with `.html(res.review)`. Instructions asking AI not to output scripts do not sanitize HTML. Impact: model output influenced by resume content can enter the DOM with unsafe markup. Fix: structured JSON rendered with textContent, or a strict server HTML sanitizer that preserves explicitly permitted suggestion attributes. Evidence: complete source path confirmed; no browser exploit executed.

4. **Audio transcription has no explicit file-size/type bounds.** InterviewTranscription::process reads the entire uploaded file and base64-encodes it after checking only upload validity. It does not enforce supported audio MIME types or an application-level size limit. Combined with finding 1, this permits unauthenticated memory/provider-cost abuse within hosting upload limits. Fix: pre-read MIME/size validation, duration limits and per-user quotas. Evidence: source-confirmed.

## Mock interview

5. **Completion, XP and persistence disagree.** `mock-interview-session.php:2480–2497` displays completion and +120 XP but the save operation is only a DEV comment. Its fetch calls cover questions, speech, replies and transcription; it never calls the actual evaluate-interview endpoint. Dashboard history therefore does not receive this page's completed sessions. Fix: server-issued session ID and real completion/evaluation save; award XP only after confirmed persistence. Evidence: source-confirmed.

6. **Candidate context never reaches the question prompt.** InterviewQuestions::buildPrompt returns a heredoc at line 253, before the candidate-context append at lines 303–307. Fix: assign the heredoc to `$prompt`, append context, then return. Evidence: reproduced locally through Reflection, invoking the actual private method with a unique context marker; returned text omitted it.

7. **Interview evaluation does not check its database insert.** CareerToolsController::evaluateInterview calls insert, reads getInsertID and returns the evaluation without verifying save success. Retries also have no session completion uniqueness guard. Fix: check persistence and use an idempotent server session record. Evidence: source-confirmed; database failure not injected.

8. **Disabling career tools does not disable their underlying API routes.** FeatureGateFilter's feature_ai_career_tools condition covers candidate/career-tools but not api/interview. Candidate resume-builder alias and coach/generate also fall outside their respective feature checks. Fix: central route classification shared by feature, subscription and authentication enforcement. Evidence: route/filter comparison.

## Salary negotiation

9. **Conversation history repeats the current answer.** `salary-negotiation.php:1443–1450` maps all S.turns to the current `text`, assigning alternating user/model roles by index instead of sending the actual conversation. Fix: retain a separate bounded conversation array with actual recruiter and candidate messages. Evidence: source-confirmed.

10. **Report saving silently ignores HTTP and application failures.** `salary-negotiation.php:1615` fires fetch without reading status/JSON and catches only network rejection. UI reports readiness before persistence. Fix: await save, display failure/retry state, avoid claiming saved history until confirmed. Evidence: source-confirmed.

11. **Scores and outcomes are accepted from the browser without validation.** CareerToolsController::saveNegotiationSession casts browser-supplied scores, salaries and round counts; only job_title is checked. Arbitrary values and duplicate submissions can pollute history and derived statistics. Fix: bounded field validation, transcript schema checks, server-calculated or explicitly untrusted practice scores, session uniqueness. Evidence: source-confirmed.

## Resume builder / other AI output

12. **Tailoring invents an 85 match score when the model supplies none.** ResumeController::tailorResume assigns 85 both for normalized flat output and as a default response score. Missing measurement is presented as a measured result. Fix: omit/mark score unavailable, or calculate it using a documented method. Evidence: source-confirmed.

13. **CV parsing accepts arbitrary file extensions and weak PDF extraction.** ResumeController::parseCvFile trusts the client extension, reads arbitrary files in its else branch, extracts PDF text through regex/raw bytes, and reads DOCX document.xml without an uncompressed-entry limit. No explicit upload-size limit appears in the handler. Impact: poor imports and unnecessary memory/provider load; legacy binary DOC is presented as supported despite no real DOC parser. Fix: allowlist and file-size checks, robust PDF extraction, bounded DOCX expansion, reject unsupported legacy DOC with a clear message. Evidence: source-confirmed; crafted uploads not executed.

14. **Resume child writes can fail model validation without failing the transaction.** ResumeController::save deletes and reinserts experience/education/skills but does not check each insert result. SQL transaction status does not detect a model returning false before issuing SQL. Fix: check each write result and roll back, preserving original rows. Ownership checks are present and should be retained. Evidence: source-confirmed; validation failure not injected.

## Positive checks and limits

- Resume save checks ownership for existing resume IDs before updating.
- Application-specific interview context queries both application ID and candidate ownership.
- Salary negotiation stores the authenticated user ID rather than accepting a posted user ID.
- General resume save wraps its SQL changes in a transaction and checks SQL transaction status.
- Frontend fallback question/voice behavior exists; availability is not proof that the live provider worked.
- Provider model availability, keys, quotas, billing, live response quality, microphones, webcam permissions, PDF/DOCX rendering and actual subscription configurations were not tested. No external model documentation claims are made in this report.

Recommended sequence: secure API access and CSRF; remove unsafe HTML insertion and bound uploads; wire interview persistence; fix conversation history and save feedback; validate scores and remove invented measurements; exercise import/export and failure paths using provider stubs before a controlled live-provider smoke test.
