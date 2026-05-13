# Vue 3 + Vite

This template should help get you started developing with Vue 3 in Vite. The template uses Vue 3 `<script setup>` SFCs, check out the [script setup docs](https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup) to learn more.

Learn more about IDE Support for Vue in the [Vue Docs Scaling up Guide](https://vuejs.org/guide/scaling-up/tooling.html#ide-support).

## Database setup

Database setup for the planned Mercury-hosted MariaDB deployment should live in `database/setup.sql`.

The current file is a placeholder for the future schema. The Vue frontend should not connect directly to MariaDB because database credentials must stay server-side. A backend API can be added later when the database schema and hosting requirements are finalised.
