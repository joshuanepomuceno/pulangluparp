=== PLRP Economy (SRP Price List) ===
Contributors: pulanglupa
Tags: crafting, economy, price list, roleplay
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

Manages Pulang Lupa RP's crafting economy as editable WordPress data, and
publishes it as a searchable public price list via shortcode.

== Description ==

This plugin recreates the "REVISED_PLRP_SRP_WORKING SHEET" spreadsheet's
pricing logic inside WordPress:

* **Materials** - the master list of raw material base prices ("ECO-BASE
  PRICES" in the sheet). Admin-editable.
* **Professions** - one per crafting job (Pagkain, Panday, Armero, Doctor,
  etc.), each with its own markup % and an optional "Publish as SRP" mode
  that mirrors the sheet's buffer/margin/round-up layer (ES-AR-PEE).
* **Items** - craftable items, each belonging to a profession, with a
  recipe of ingredient lines (material + quantity).

All costs and selling prices are computed live from these three pieces, so
editing a base material's price or a profession's markup immediately
updates every recipe that depends on it - no manual re-calculation needed.

== Getting Started ==

1. Activate the plugin. This creates four new database tables.
2. Go to **PLRP Economy > Import Spreadsheet** and upload the working
   sheet (.xlsx) once, to seed Materials, Professions, and Items.
3. Review the import report for any warnings (e.g. an ingredient that
   wasn't found in ECO-BASE PRICES and was auto-created with a price of 0).
4. From then on, edit prices and recipes directly under **PLRP Economy >
   Materials / Professions / Items** - there's no need to re-import.
5. Add `[plrp_price_list]` to any page or post to display the public,
   searchable, tabbed price list. Use `[plrp_price_list profession="panday"]`
   to show just one profession, or `show_ingredients="no"` to hide the
   recipe breakdown column.

== Notes on the calculation model ==

Per ingredient: `line_cost = quantity * material.base_price`.
Per item: `total_mat_cost = sum(line_cost)`, `markup = total_mat_cost *
profession.markup_pct`, `selling_price = total_mat_cost + markup`.

If a profession has "Publish as SRP" enabled, the displayed price instead
goes through the extra layer seen in the sheet's ES-AR-PEE tab:
`buffered = total_mat_cost * (1 + buffer_pct)`, `margin = buffered *
margin_pct`, `srp = round_up(buffered + margin, rounding_increment)`.
Defaults (15% buffer, 25% margin, round up to 0.1) match what the original
sheet used, but every profession can be tuned independently under
**PLRP Economy > Professions**.
