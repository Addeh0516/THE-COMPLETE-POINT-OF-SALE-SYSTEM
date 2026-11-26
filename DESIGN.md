# Afterhours Arcade — Design Context

## Creative direction

Afterhours Arcade is a cashier tool that feels like the back room of an arcade after midnight: functional screens, quiet phosphor glow, and the occasional bright signal. The interface should feel considered and tactile, but never like a game that slows a cashier down.

## Palette

- `--ink`: `#0B0B12` — charcoal page background.
- `--navy`: `#111629` — deep navy shell and panels.
- `--plum`: `#2A2140` — muted purple depth layer.
- `--mint`: `#B6F5D8` — primary positive/action accent.
- `--lavender`: `#C9B8FF` — selected and secondary accent.
- `--signal`: `#FFB86B` — warning/attention only.
- `--paper`: `#F4F1EA` — warm high-contrast text.
- `--muted`: `#A4A2B3` — secondary text that stays legible on navy.

## Typography

- Display: `Bricolage Grotesque`, with generous line height and a relaxed, editorial scale.
- Body: `DM Sans`, for friendly density and readable forms.
- Utility/data: `IBM Plex Mono`, only for IDs, timestamps, stock counts, and small system labels.

## Layout grammar

The authenticated shell uses a narrow left rail and a fluid content canvas. Panels use 14–18px corners, hairline lavender/navy borders, and soft offset depth. Lists are calm and dense enough for operations; cards are reserved for genuine summaries and status groupings. Mobile collapses the rail into a top bar and preserves one-column form flow.

## Signature element

The “scanline ledger”: a thin animated mint line behind the dashboard’s primary total and sale controls, echoing an arcade cabinet display without turning the screen into a neon poster. Motion is ambient and disabled under `prefers-reduced-motion`.

## Image direction

Use one decisive arcade photo on the dashboard and restrained square thumbnails in product lists. Images are allowed to be dark and atmospheric; content sits on a solid navy surface so readability never depends on the photo.

## Interaction contract

- Primary actions use sentence case and concrete verbs: `Record sale`, `Add product`, `Save changes`.
- Destructive actions are POST/DELETE forms with confirmation text, not GET links.
- Flash feedback appears at the top of the content canvas and is dismissible by keyboard.
- Validation errors stay beside their field and keep prior input.
- Stock is described with both a label and a quantity; color never carries the only meaning.
- All focusable elements have a visible mint/lavender focus ring.

## Token ownership

This file is the source of truth for the CSS custom properties in `public/css/arcade.css`. Blade templates consume classes and do not introduce competing theme values.
