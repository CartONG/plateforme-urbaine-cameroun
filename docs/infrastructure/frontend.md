# Front-End Architecture Vue 3 / Vuetify / Pinia

This architecture aims to clearly separate responsibilities to facilitate maintenance, testing, and the evolution of the application. It is based on Vue 3, Vuetify, Pinia, i18n, and a custom Axios instance for managing HTTP requests.

## Technologies Used

- **Vue 3**: A modern JavaScript framework for building the user interface.  
- **Vuetify**: Material Design-based UI components for a consistent and aesthetic interface.  
- **Pinia**: Centralized state management for the application.  
- **i18n**: Internationalization module to support multiple languages.  
- **Axios**: Centralized instance for making HTTP requests to the backend.  

## Architecture Structure

The application is organized into four main blocks:

### 1. Generic Components  

**Role**: Serve as the building blocks of the user interface.  

**Characteristics**:  
- Independent and reusable.  
- Based on Vuetify to ensure visual consistency.  
- Do not contain business logic or direct calls to external services.  

**Examples**: Buttons, input fields, modals, cards, etc.  

### 2. Views  

**Role**: Represent the pages or sections of the application.  

**Characteristics**:  
- Assemble multiple generic and specific components to form a complete view.  
- Interact with stores to retrieve and update state.  
- Can integrate services for specific tasks (e.g., form validation).  

**Examples**: Home page, Actors page, Projects page, Map, etc.  

### 3. Stores (Pinia)  

**Role**: Centralize state management and business logic.  

**Characteristics**:  
- Store the global and local state of the application.  
- Manage service calls to communicate with external systems.  
- Enable optimal reactivity and state sharing between different views.  

**Examples**: User store, Map store, Actors store.  

### 4. Services  

**Role**: Encapsulate logic for data processing and communication with the backend or other APIs.  

**Characteristics**:  
- Implemented as classes with static methods.  
- Use a centralized Axios instance for making HTTP requests.  
- Perform complex operations without overloading components or stores.  

**Examples**: Authentication service, forms validation, external API call service.  

## Data Flow and Interactions  

- **Components** focus on display and local interactions.  
- **Views** orchestrate these components and delegate business logic to stores.  
- **Stores** handle state management and call services for asynchronous operations.  
- **Services** communicate with the backend via Axios and return results to stores.  

## Internationalization Management (i18n)  

- Interface texts and labels are managed through translation files.  
- The i18n module allows for easy language switching without modifying components.  
- This approach ensures an adaptable interface for different markets and users.  


<iframe style="border: 1px solid rgba(0, 0, 0, 0.1);" width="600" height="600" src="https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fboard%2FzFS6Ok8onp5b1AniszpVhw%2FExpertise-France%3Fnode-id=359-324&t=KoluBWUkIpjXsM6v-0" allowfullscreen></iframe>