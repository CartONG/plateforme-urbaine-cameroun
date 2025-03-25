# Architecture with Symfony and API Platform  

This architecture is based on **Symfony** and **API Platform**, with centralized entity management and access control handled via annotations. All business logic and specific processing are delegated to **services** (including **Providers** and **Processors**). **Controllers** are only used for specific cases, such as PDF generation.  

## Technologies Used  

- **Symfony**: A structured PHP framework based on the MVC architecture.  
- **API Platform**: Automatically generates REST and GraphQL APIs.  
- **Doctrine ORM**: Manages entities and interactions with PostgreSQL.  
- **JWT Authentication**: Secures API endpoints using JSON Web Tokens.  

## Architecture Structure  

The application is built around three main components:  

### 1. Entities & Security (Access Control via Annotations)  

**Role**: Define the data structure and enforce access rules through annotations.  

**Characteristics**:  
- **Entities** represent database tables (Doctrine ORM).  
- API Platform automatically generates API endpoints.  
- **Security** is managed via **annotations** in entities:  
  - `#[ApiResource(security: "is_granted('ROLE_ADMIN')")]`  
  - `#[ApiProperty(readable: false, writable: true)]`  

**Examples**:  
- `User` entity with restricted access to personal data.  
- `Project` entity editable only to authorized users.  

### 2. Services (Providers, Processors & Others)  

**Role**: Centralize business logic and specific processing.  

#### **Providers (Data Providers)**  
- Modify how entity data is retrieved.  
- Replace repositories to provide enriched data.  

**Example**:  
- `UserProvider` filters users based on their validation status and roles.  

#### **Processors (Data Processors)**  
- Handle pre-processing before storing data (POST, PUT, DELETE).  
- Used for operations like password hashing or sending notifications.  

**Example**:  
- `UserProcessor` hashes the password before saving the user.  

#### **Other Services**  
- Encapsulate business logic independent of entities.  
- Can be called by **Providers** and **Processors**.  

**Examples**:  
- `AuthService` for JWT management.  
- `PDFService` for generating server-side documents.  
- `ExternalApiService` for interacting with external APIs.  

### 3. Routes / Controllers / Views (Specific Use Cases)  

**Role**: Handle operations that do not fit within the standard API model.  

**Use Cases**:  
- File generation (PDF, CSV, etc.).  
- Executing specific server-side actions.  

**Examples**:  
- `PDFController` generates a document and returns it as a download.  

## Data Flow and Interactions  

1. API Platform receives a request and applies entity access rules.  
2. A **Provider** modifies the retrieved data if necessary.  
3. A **Processor** applies business logic before saving data.  
4. **Services** handle complex business logic and external API interactions.  
5. API Platform returns a properly formatted JSON response.  

## API Platform and Security  

- **JWT Authentication** secures API access.  
- **Annotations** manage access control directly in entities.  
- **Voters and Policies** allow for advanced permission handling.  

