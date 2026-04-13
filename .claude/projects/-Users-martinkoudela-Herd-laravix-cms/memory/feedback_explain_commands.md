---
name: Explain commands before running
description: User wants to know what each command does before it's executed
type: feedback
---

Always briefly explain what a command does before running it, especially less obvious ones like `vendor/bin/pint`.

**Why:** User got confused when pint was run without explanation.
**How to apply:** Before running any non-obvious command (pint, artisan commands, etc.), add a short line explaining what it does.