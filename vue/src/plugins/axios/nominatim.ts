import axios, { type AxiosInstance } from 'axios'

const axiosInstance = axios.create({
  baseURL: 'https://' + window.location.hostname + '/nominatim'
})

export const nominatimClient: AxiosInstance = axiosInstance
