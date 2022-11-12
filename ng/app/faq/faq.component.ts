import { Component, OnInit } from '@angular/core';
import { AnimeService } from '../services/anime.service';

@Component({
	selector: 'app-faq',
	templateUrl: './faq.component.html',
	styleUrls: ['./faq.component.scss']
})
export class FaqComponent implements OnInit {

	site$: string;

	constructor(private animeService: AnimeService) {
		this.site$ = "http://richard.co.zw";
	}

	ngOnInit(): void {
		setTimeout(() => {
			this.animeService.reviewComponents();
		}, 0);
	}

}
