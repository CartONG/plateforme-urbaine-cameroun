import axios, { type AxiosInstance } from 'axios'

const axiosInstance = axios.create({
  baseURL: 'https://' + import.meta.env.VITE_DOMAIN + '/nominatim'
})

export const nominatimClient: AxiosInstance = axiosInstance
