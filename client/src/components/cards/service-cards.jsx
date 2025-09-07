import Image from "next/image"
import Link from "next/link"
import styles from "./service-cards.module.css"

const services = [
  {
    id: 1,
    title: "Pet Veterinary",
    description: "Professional veterinary care for your beloved pets with experienced doctors and modern facilities.",
    image: "/pet-vet.jpg",
    link: "/appointment",
  },
  {
    id: 2,
    title: "Pet Shelter",
    description: "Safe and comfortable shelter services for pets in need of temporary or permanent care.",
    image: "/shelter.jpg",
    link: "/emergency",
  },
  {
    id: 3,
    title: "Pet Grooming",
    description: "Complete grooming services to keep your pets clean, healthy, and looking their best.",
    image: "/grooming.jpg",
    link: "/appointment",
  },
  {
    id: 4,
    title: "Lost Pet Recovery",
    description: "Help find your lost pets with our comprehensive search and recovery network.",
    image: "/lostpet.jpg",
    link: "/lost-pet",
  },
]

export default function ServiceCards() {
  return (
    <div className={styles.cardsContainer}>
      <h2 className={styles.sectionTitle}>Our Services</h2>
      <div className={styles.cardsGrid}>
        {services.map((service) => (
          <div key={service.id} className={styles.card}>
            <div className={styles.cardImage}>
              <Image
                src={service.image || "/placeholder.svg"}
                alt={service.title}
                width={300}
                height={300}
                className={styles.image}
              />
            </div>
            <div className={styles.cardContent}>
              <h3 className={styles.cardTitle}>{service.title}</h3>
              <p className={styles.cardDescription}>{service.description}</p>
              <Link href={service.link} className={styles.cardButton}>
                Learn More
              </Link>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}
