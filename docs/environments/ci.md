# Continuous Integration

## Workflows

**Github Workflows** can be found [here](https://github.com/CartONG/plateforme-urbaine-cameroun/actions),
they are setup in the `.github/workflows` [folder](https://github.com/CartONG/plateforme-urbaine-cameroun/tree/main/.github/workflows)

<iframe style="border: 1px solid rgba(0, 0, 0, 0.1);" width="800" height="450" src="https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fboard%2FzFS6Ok8onp5b1AniszpVhw%2FExpertise-France%3Fnode-id%3D57-748%26t%3DgMD4VH1Yzsn47pko-1" allowfullscreen></iframe>

::: tip
**🛠 Differences Between Local Execution and Build in a Vue Application with TypeScript**

When developing a Vue application with TypeScript, it may run smoothly in local development but fail during the build process (`vite build` or `vue-tsc --noEmit`).

This happens because **type checking behaves differently** in development and production modes:

1️⃣ **In Development Mode (`vite dev`)**  
- Vite uses **esbuild** for TypeScript transpilation, which **does not perform strict type checking**.  
- Only syntax or import errors are detected, meaning some type-related issues may go unnoticed.

2️⃣ **During Build (`vite build`)**  
- The build process relies on **Vue TSC** (`vue-tsc --noEmit`), which performs **full TypeScript type checking**.  
- Errors that were not visible during development may appear, such as:  
  - Incorrect types in props, emits, or computed properties.  
  - Inconsistencies in interfaces or generic types.  
  - Accessing properties that are missing from the expected type.  
:::
