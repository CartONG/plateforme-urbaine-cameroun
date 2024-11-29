# Request the API

To request the API, simply import the **axios instance**, in order to have all the pre-configured configs including `baseUrl`, error interceptors ...

``` ts
import apiClient from '@/assets/plugins/axios/api'

const projects = await apiClient.get('/api/projects')
```