export const onInvalidSubmit = () => {
  const el = document.querySelector(`.v-input--error`)
  if (el) {
    el.scrollIntoView({
      behavior: 'smooth',
      block: 'center',
      inline: 'nearest'
    })
  }
}
