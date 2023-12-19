import type { Metadata } from 'next'
import './globals.css'

export const metadata: Metadata = {
  title: '商家中心',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  )
}
