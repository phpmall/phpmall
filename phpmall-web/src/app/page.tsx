import Link from 'next/link'
import styles from './page.module.css'

export default function Home() {
  return (
    <main>
      <div>
        welcome
        
        <Link href="/login">登录</Link> | 
        <Link href="/signup">免费注册</Link>
      </div>
    </main>
  )
}
