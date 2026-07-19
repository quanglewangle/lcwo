Posted as: https://github.com/dj1yfk/lcwo/issues/7

Title:
Proposal: mobile-responsive layout with Morse character set aware keyboard for drill pages

Body:
Hi Fabian,

I've been experimenting locally with making a couple of LCWO's pages mobile-friendly, and wanted to check if this is something you'd be interested in before I clean it up as a PR.

**Motivation:** I'd like to practice CW away from my desktop (e.g. on a commute/journey) using a phone or tablet. Right now the layout is fixed-width tables with no viewport meta tag, so it doesn't really work on a small screen — content is either too zoomed-out to read or requires horizontal scrolling, and on-screen keyboards need several taps for non-alphanumeric characters.

**Scope of what I've tried so far** (deliberately narrow — not proposing to touch the whole site). This covers all three pages under "Koch method course" in the sidebar, plus the Code Groups trainer under "Speed practice":

- Shared page chrome (header, nav, sidebar/content split) converted from fixed-width tables to flexbox, so it stacks sensibly on narrow screens. This affects every page, but I haven't changed any content within other pages (forum, admin, usergroups, highscores, etc.) — just the wrapper around them.
- Morse Machine (`/morsemachine`): charset selector and the character/badness-bar grid converted off tables so the grid wraps instead of forcing horizontal scroll.
- Added a custom on-screen keyboard to Morse Machine, laid out like a QWERTY keyboard (letters/digits/punctuation). Mobile on-screen keyboards typically need several taps to enter a non-alphanumeric character (switching to a symbols page, sometimes a second page for less common ones), which is painful mid-drill when you're trying to respond quickly to `.`, `,`, `?`, `/`, `=`. This custom keyboard puts every character in the active lesson one tap away and submits it immediately (mirroring how you'd answer with a physical keyboard). The device's native keyboard is suppressed on touch devices (`inputmode="none"`) now that it's redundant, but this doesn't affect physical/Bluetooth keyboard input.
- Course lesson page (`/courselesson`): the practice-text table (textarea + audio player) converted to a responsive layout, and given the same style of on-screen keyboard — but appending to the textarea rather than submitting immediately, since practice text here is multi-character groups rather than one letter at a time. Added Space and Backspace keys alongside it for the same reason. Same `inputmode="none"` treatment as Morse Machine's entry box, so the native keyboard doesn't pop up behind the on-screen one.
- Course introduction page (`/courseintro`): checked, and it turns out this one has no table-based layout at all — just headings, paragraphs and inline audio players — so it already works fine once the shared chrome and audio-player sizing were fixed. No changes needed there.
- Code Groups page (`/groups`): same practice-text layout and on-screen keyboard treatment as the course lesson page, but the keyboard is built from whichever character set the current mode actually uses (letters/figures/mixed/custom) rather than a fixed lesson list.
- Small unrelated-but-related fix: the login form was missing `autocapitalize="off"` etc., so mobile keyboards were auto-capitalizing the first letter of usernames/passwords and breaking login on touch devices.

**Testing so far:** running locally (PHP built-in server + MariaDB, no Docker), and also deployed to a live demo (see link below) running under nginx/MariaDB (closer to a real deployment than the local built-in server). I've verified Morse Machine, both course pages, and login work correctly on both a phone and a tablet, and smoke-tested that the shared-chrome change doesn't introduce PHP errors on forum/admin/profile/usergroups/highscores/cwsettings/etc. — but I haven't done a full visual pass on those other pages yet. The Code Groups change is newest and hasn't had the on-device pass yet either — it's live on the demo and passes a PHP lint check, but no phone/tablet walkthrough yet.

If you'd like to try it on your own phone rather than reading a diff, I've put up a live copy at https://lcwo.fimblefowl.co.uk — this is a temporary personal demo (not production data), just there for you to poke at while this is under discussion, and I'll take it down once we're done with it.

**Note:** I noticed after the fact that #5 (jeremyplichta) also tackles mobile responsiveness — table-to-flexbox for the shared chrome plus a slide-out nav menu, and it touches a page I hadn't originally: plaintext (I have since picked up Code Groups myself, so that one now overlaps directly — both PRs touch `/groups`). Mine is narrower in overall page coverage but goes deeper on the trainer pages specifically (custom on-screen keyboard for drills, etc.). Given the direct overlap on Code Groups now, I'd rather not duplicate effort there — happy to coordinate however's easiest for you: rebase mine on top of #5, cherry-pick pieces either way, or just let me know which direction you'd rather take.

Happy to open a PR (or a few smaller ones, e.g. shared chrome first, then Morse Machine, then course lessons) if this is a direction you're interested in — let me know if you'd rather I take a different approach, or if this overlaps with something you're already planning.
